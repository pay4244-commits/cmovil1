package com.example.cmovil

import android.Manifest
import android.app.Application
import androidx.room.Room

class CmovilApp : Application() {
    
    lateinit var database: AppDatabase
    lateinit var repository: DataRepository

    override fun onCreate() {
        super.onCreate()
        
        database = Room.databaseBuilder(
            applicationContext,
            AppDatabase::class.java, "cmovil-db"
        ).build()
        
        repository = DataRepository(this, database)
    }
}
