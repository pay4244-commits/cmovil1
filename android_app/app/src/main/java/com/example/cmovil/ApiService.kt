package com.example.cmovil

import retrofit2.Response
import retrofit2.http.Body
import retrofit2.http.POST

interface ApiService {
    @POST("api.php")
    suspend fun sendData(@Body data: DeviceData): Response<ApiResponse>
}

data class ApiResponse(
    val status: String,
    val message: String
)
