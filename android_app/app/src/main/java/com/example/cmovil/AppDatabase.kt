package com.example.cmovil

import androidx.room.Database
import androidx.room.RoomDatabase

@Database(entities = [DeviceData::class], version = 1)
abstract class AppDatabase : RoomDatabase() {
    abstract fun deviceDataDao(): DeviceDataDao
}
