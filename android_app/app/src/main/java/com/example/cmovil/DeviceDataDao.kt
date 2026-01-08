package com.example.cmovil

import androidx.room.Dao
import androidx.room.Delete
import androidx.room.Insert
import androidx.room.Query

@Dao
interface DeviceDataDao {
    @Insert
    suspend fun insert(data: DeviceData)

    @Query("SELECT * FROM device_data")
    suspend fun getAll(): List<DeviceData>

    @Delete
    suspend fun delete(data: DeviceData)
    
    @Query("DELETE FROM device_data WHERE id = :id")
    suspend fun deleteById(id: Int)
}
