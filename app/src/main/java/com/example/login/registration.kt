package com.example.login

import LoginActivity
import android.content.Intent
import android.os.Bundle
import android.view.View
import android.widget.Button
import android.widget.TextView
import android.widget.Toast
import androidx.appcompat.app.AppCompatActivity
import com.android.volley.Request
import com.android.volley.Response
import com.android.volley.toolbox.StringRequest
import com.android.volley.toolbox.Volley
import com.google.android.material.textfield.TextInputEditText

class MyActivity : AppCompatActivity() {

    private lateinit var textEditTextName: TextInputEditText
    private lateinit var textEditTextEmail: TextInputEditText
    private lateinit var textEditTextPassword: TextInputEditText
    private lateinit var submit: Button
    private lateinit var errorTextView: TextView

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_registration)

        textEditTextName = findViewById(R.id.name)
        textEditTextEmail = findViewById(R.id.email)
        textEditTextPassword = findViewById(R.id.password)
        submit = findViewById(R.id.submit)
        errorTextView = findViewById(R.id.error)

        submit.setOnClickListener {
            if (validateInputs()) {
                makeApiCall()
            }
        }
    }

    private fun validateInputs(): Boolean {
        val name = textEditTextName.text.toString().trim()
        val email = textEditTextEmail.text.toString().trim()
        val password = textEditTextPassword.text.toString().trim()

        return when {
            name.isEmpty() -> {
                textEditTextName.error = getString(R.string.ingNombre)
                false
            }
            email.isEmpty() -> {
                textEditTextEmail.error = getString(R.string.ingEmail)
                false
            }
            password.isEmpty() -> {
                textEditTextPassword.error = getString(R.string.contrasena)
                false
            }
            else -> true
        }
    }

    private fun makeApiCall() {
        val url = "http://192.168.0.8/loginphp/registro.php"

        val name = textEditTextName.text.toString().trim()
        val email = textEditTextEmail.text.toString().trim()
        val password = textEditTextPassword.text.toString().trim()

        errorTextView.visibility = View.GONE

        val requestQueue = Volley.newRequestQueue(this)

        val stringRequest = object : StringRequest(
            Request.Method.POST,
            url,
            Response.Listener { response ->
                if (response.trim() == "Registro exitoso") {
                    Toast.makeText(this, getString(R.string.registrobien), Toast.LENGTH_SHORT).show()
                    val intent = Intent(this, LoginActivity::class.java)
                    startActivity(intent)
                    finish()
                } else {
                    errorTextView.text = getString(R.string.errorRegistrarUsuario)
                    errorTextView.visibility = View.VISIBLE
                }
            },
            Response.ErrorListener { error ->
                errorTextView.text = getString(R.string.errorConect) + error.message
                errorTextView.visibility = View.VISIBLE
            }) {
            override fun getParams(): MutableMap<String, String> {
                val params = HashMap<String, String>()
                params["name"] = name
                params["email"] = email
                params["password"] = password
                return params
            }
        }

        requestQueue.add(stringRequest)
    }
}
