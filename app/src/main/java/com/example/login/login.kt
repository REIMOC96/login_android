import android.content.Intent
import android.os.Bundle
import android.widget.Toast
import androidx.appcompat.app.AppCompatActivity
import com.android.volley.Request
import com.android.volley.Response
import com.android.volley.toolbox.JsonObjectRequest
import com.android.volley.toolbox.Volley
import com.example.login.databinding.ActivityLoginBinding
import org.json.JSONObject

class LoginActivity : AppCompatActivity() {

    private lateinit var binding: ActivityLoginBinding

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        binding = ActivityLoginBinding.inflate(layoutInflater)
        setContentView(binding.root)

        binding.buttonLogin.setOnClickListener {
            val email = binding.editTextEmail.text.toString()
            val password = binding.editTextPassword.text.toString()

            loginUser(email, password)
        }

        binding.textViewRegisterNow.setOnClickListener {
            // Redirigir a la actividad de registro
            val intent = Intent(this, registration::class.java)
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
            Response.Listener { response ->
                val status = response.getString("status")
                if (status == "success") {
                    val intent = Intent(this, DashboardActivity::class.java)
                    startActivity(intent)
                    finish()
                } else {
                    val message = response.getString("message")
                    Toast.makeText(this, message, Toast.LENGTH_SHORT).show()
                }
            },
            Response.ErrorListener { error ->
                Toast.makeText(this, "Error: ${error.message}", Toast.LENGTH_SHORT).show()
            })

        Volley.newRequestQueue(this).add(request)
    }
}
