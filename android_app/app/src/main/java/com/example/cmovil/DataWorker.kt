package com.example.cmovil

import android.Manifest
import android.content.Context
import android.content.Intent
import android.content.IntentFilter
import android.content.pm.PackageManager
import android.location.Location
import android.os.BatteryManager
import android.os.Build
import android.telephony.TelephonyManager
import android.util.Log
import androidx.core.app.ActivityCompat
import androidx.work.CoroutineWorker
import androidx.work.WorkerParameters
import com.google.android.gms.location.LocationServices
import com.google.android.gms.location.Priority
import com.google.android.gms.tasks.Tasks
import kotlinx.coroutines.Dispatchers
import kotlinx.coroutines.withContext
import java.text.SimpleDateFormat
import java.util.Date
import java.util.Locale

class DataWorker(appContext: Context, workerParams: WorkerParameters) :
    CoroutineWorker(appContext, workerParams) {

    private val repository = (appContext.applicationContext as CmovilApp).repository
    private val context = appContext

    override suspend fun doWork(): Result = withContext(Dispatchers.IO) {
        try {
            Log.d("DataWorker", "Starting data collection...")
            val data = collectData()
            repository.collectAndSendData(data)
            Result.success()
        } catch (e: Exception) {
            Log.e("DataWorker", "Error in doWork: ${e.message}")
            Result.retry()
        }
    }

    private fun collectData(): DeviceData {
        // Battery
        val batteryStatus: Intent? = IntentFilter(Intent.ACTION_BATTERY_CHANGED).let { ifilter ->
            context.registerReceiver(null, ifilter)
        }
        val level: Int = batteryStatus?.getIntExtra(BatteryManager.EXTRA_LEVEL, -1) ?: -1
        val scale: Int = batteryStatus?.getIntExtra(BatteryManager.EXTRA_SCALE, -1) ?: -1
        val batteryPct = if (level != -1 && scale != -1) (level * 100 / scale.toFloat()).toInt() else 0
        
        val status: Int = batteryStatus?.getIntExtra(BatteryManager.EXTRA_STATUS, -1) ?: -1
        val isCharging = status == BatteryManager.BATTERY_STATUS_CHARGING ||
                status == BatteryManager.BATTERY_STATUS_FULL

        // Telephony (Requires READ_PHONE_STATE)
        var phoneNumber: String? = null
        var deviceId = Build.ID // Fallback ID
        
        // Note: Accessing IMEI/Phone Number is highly restricted in Android 10+
        // We use Build.ID or a generated UUID typically, but here we try to follow requirements if permission exists
        if (ActivityCompat.checkSelfPermission(context, Manifest.permission.READ_PHONE_STATE) == PackageManager.PERMISSION_GRANTED) {
            try {
                val telephonyManager = context.getSystemService(Context.TELEPHONY_SERVICE) as TelephonyManager
                // These are deprecated/restricted but added for completeness of requirement
                if (Build.VERSION.SDK_INT < Build.VERSION_CODES.Q) {
                     // Legacy ways to get ID, usually blocked now
                     // deviceId = telephonyManager.deviceId ?: Build.ID
                     // phoneNumber = telephonyManager.line1Number
                }
            } catch (e: Exception) {
                Log.w("DataWorker", "Could not access telephony data: ${e.message}")
            }
        }
        
        // Location
        var lat = 0.0
        var lon = 0.0
        var alt = 0.0
        
        if (ActivityCompat.checkSelfPermission(context, Manifest.permission.ACCESS_FINE_LOCATION) == PackageManager.PERMISSION_GRANTED) {
            try {
                val fusedLocationClient = LocationServices.getFusedLocationProviderClient(context)
                
                // Synchronous wait for location (not recommended on main thread, but OK in Worker)
                val locationTask = fusedLocationClient.getCurrentLocation(Priority.PRIORITY_HIGH_ACCURACY, null)
                val location: Location? = Tasks.await(locationTask)
                
                if (location != null) {
                    lat = location.latitude
                    lon = location.longitude
                    alt = location.altitude
                }
            } catch (e: Exception) {
                Log.e("DataWorker", "Location error: ${e.message}")
            }
        }

        return DeviceData(
            deviceId = Build.SERIAL ?: Build.ID, // Unique ID fallback
            phoneNumber = phoneNumber,
            model = Build.MODEL,
            brand = Build.MANUFACTURER,
            osVersion = Build.VERSION.RELEASE,
            batteryLevel = batteryPct,
            isCharging = isCharging,
            latitude = lat,
            longitude = lon,
            altitude = alt,
            timestamp = SimpleDateFormat("yyyy-MM-dd HH:mm:ss", Locale.getDefault()).format(Date())
        )
    }
}
