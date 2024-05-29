package com.example.login
import android.content.Context
import com.android.volley.Request
import com.android.volley.RequestQueue
import com.android.volley.toolbox.JsonObjectRequest
import com.android.volley.toolbox.Volley
import org.json.JSONObject

class RequestQueueSingleton constructor(context: Context) {
    companion object {
        @Volatile
        private var INSTANCE: RequestQueueSingleton? = null
        fun getInstance(context: Context) =
            INSTANCE ?: synchronized(this) {
                INSTANCE ?: RequestQueueSingleton(context).also {
                    INSTANCE = it
                }
            }
    }

    private val requestQueue: RequestQueue by lazy {
        Volley.newRequestQueue(context.applicationContext)
    }

    fun <T> addToRequestQueue(req: Request<T>) {
        requestQueue.add(req)
    }
}

fun login(email: String, password: String, context: Context, callback: (JSONObject) -> Unit) {
    val url = "http://tu_servidor/login.php"
    val jsonBody = JSONObject()
    jsonBody.put("email", email)
    jsonBody.put("password", password)

    val jsonObjectRequest = JsonObjectRequest(Request.Method.POST, url, jsonBody,
        { response ->
            callback(response)
        },
        { error ->
            // Manejar errores de la solicitud
            error.printStackTrace()
        }
    )

    RequestQueueSingleton.getInstance(context).addToRequestQueue(jsonObjectRequest)
}
