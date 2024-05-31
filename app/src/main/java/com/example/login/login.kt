package com.example.login

import android.content.Intent
    import android.os.Bundle
    import android.widget.Button
    import android.widget.EditText
    import android.widget.TextView
    import android.widget.Toast
    import androidx.appcompat.app.AppCompatActivity
    import com.android.volley.Request
    import com.android.volley.toolbox.JsonObjectRequest
    import com.android.volley.toolbox.Volley
    import org.json.JSONObject

    class LoginActivity : AppCompatActivity() {

        override fun onCreate(savedInstanceState: Bundle?) {
            super.onCreate(savedInstanceState)
            setContentView(R.layout.activity_login)

            val editTextEmail = findViewById<EditText>(R.id.email)
            val editTextPassword = findViewById<EditText>(R.id.password)
            val buttonLogin = findViewById<Button>(R.id.submit)
            val textViewRegisterNow = findViewById<TextView>(R.id.registerNow)

            buttonLogin.setOnClickListener {
                val email = editTextEmail.text.toString()
                val password = editTextPassword.text.toString()

                loginUser(email, password)
            }

            textViewRegisterNow.setOnClickListener {
                // Redirigir a la actividad de registro
                val intent = Intent(this, MyActivity::class.java)
                startActivity(intent)
            }
        }
        private fun loginUser(email: String, password: String) {
            val url = "http://192.168.0.8/loginphp/login.php"
            val params = JSONObject().apply {
                put("email", email)
                put("password", password)
            }

            val request = JsonObjectRequest(Request.Method.POST, url, params,
                { response ->
                    try {
                        val status = response.getString("status")
                        val message = response.getString("message")
                        if (status == "success") {
                            // Si el inicio de sesión es exitoso, mostrar un mensaje de éxito y realizar cualquier acción adicional necesaria
                            Toast.makeText(this, message, Toast.LENGTH_SHORT).show()
                            // Aquí puedes realizar alguna acción adicional si lo deseas, como abrir una nueva actividad
                            // Por ejemplo:
                            // val intent = Intent(this, HomeActivity::class.java)
                            // startActivity(intent)
                        } else {
                            // Si el inicio de sesión falla, mostrar un mensaje de error
                            Toast.makeText(this, message, Toast.LENGTH_SHORT).show()
                        }
                    } catch (e: Exception) {
                        // Manejar cualquier excepción que pueda ocurrir al procesar la respuesta JSON
                        e.printStackTrace()
                        Toast.makeText(this, "Error al procesar la respuesta del servidor", Toast.LENGTH_SHORT).show()
                    }
                },
                { error ->
                    // Manejar errores de conexión o de solicitud
                    Toast.makeText(this, "Error: ${error.message}", Toast.LENGTH_SHORT).show()
                })

            // Agregar la solicitud a la cola de solicitudes de Volley
            Volley.newRequestQueue(this).add(request)
        }
    }
