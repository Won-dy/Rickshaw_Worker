package com.example.capstone

import android.app.Activity
import android.content.ContentValues.TAG
import android.content.Context
import android.content.Intent
import android.os.Bundle
import android.util.Log
import android.widget.*
import com.android.volley.Response
import com.android.volley.toolbox.Volley
import com.kakao.sdk.auth.LoginClient
import com.kakao.sdk.auth.model.OAuthToken
import com.kakao.sdk.common.model.AuthErrorCause.*
import com.kakao.sdk.user.UserApiClient
import kr.co.ilg.activity.findwork.MainActivity
import kr.co.ilg.activity.findwork.Sharedpreference
import kr.co.ilg.activity.login.FindPasswordInfoActivity
import kr.co.ilg.activity.login.SignupEmailActivity
import kr.co.ilg.activity.login.SignupUserInfoActivity
import org.json.JSONObject


class MainActivity : Activity() {
    lateinit var context: Context

    init {
        instance = this
    }

    companion object {
        private var instance: com.example.capstone.MainActivity? = null
        fun applicationContext(): Context {
            return instance!!.applicationContext
        }
    }

    lateinit var myJSON: String;

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.login);


        Sharedpreference.clear(applicationContext)
        val idET = findViewById<EditText>(R.id.idET);
        val pwET = findViewById<EditText>(R.id.pwET);
        val kakaoLoginBtn = findViewById<ImageButton>(R.id.kakaoLoginBtn)

        val loginBtn = findViewById<Button>(R.id.loginBtn)
        val findPwBtn = findViewById<TextView>(R.id.findPwBtn)
        val signUpBtn = findViewById<TextView>(R.id.signUpBtn)


        fun intent() {
            var intent = Intent(this, MainActivity::class.java)
            startActivity(intent) // 일반로그인 정보갖고오기

        }

        fun signup(email: String, pw: String) {
            var intent1 = Intent(application, SignupUserInfoActivity::class.java)
            intent1.putExtra("worker_email", email)
            intent1.putExtra("worker_pw", pw)
            startActivity(intent1)
        }


        val callback: (OAuthToken?, Throwable?) -> Unit = { token, error ->
            if (error != null) {
                when {
                    error.toString() == AccessDenied.toString() -> {
                        Toast.makeText(this, "접근이 거부 됨(동의 취소)", Toast.LENGTH_SHORT).show()
                    }
                    error.toString() == InvalidClient.toString() -> {
                        Toast.makeText(this, "유효하지 않은 앱", Toast.LENGTH_SHORT).show()
                    }
                    error.toString() == InvalidGrant.toString() -> {
                        Toast.makeText(this, "인증 수단이 유효하지 않아 인증할 수 없는 상태", Toast.LENGTH_SHORT).show()
                    }
                    error.toString() == InvalidRequest.toString() -> {
                        Toast.makeText(this, "요청 파라미터 오류", Toast.LENGTH_SHORT).show()
                    }
                    error.toString() == InvalidScope.toString() -> {
                        Toast.makeText(this, "유효하지 않은 scope ID", Toast.LENGTH_SHORT).show()
                    }
                    error.toString() == Misconfigured.toString() -> {
                        Toast.makeText(this, "설정이 올바르지 않음(android key hash)", Toast.LENGTH_SHORT).show()
                    }
                    error.toString() == ServerError.toString() -> {
                        Toast.makeText(this, "서버 내부 에러", Toast.LENGTH_SHORT).show()
                    }
                    error.toString() == Unauthorized.toString() -> {
                        Toast.makeText(this, "앱이 요청 권한이 없음", Toast.LENGTH_SHORT).show()
                    }
                    else -> { // Unknown
                        Toast.makeText(this, "기타 에러", Toast.LENGTH_SHORT).show()
                    }
                }
            } else if (token != null) {
                UserApiClient.instance.me { user, error ->
                    if (error != null) {
                        Log.e(TAG, "사용자 정보 요청 실패", error)
                    } else if (user != null) {
                        if (user.kakaoAccount?.email != null) {
                            Log.i(TAG, "이메일: ${user.kakaoAccount?.email}" +
                                    "\n닉네임: ${user.kakaoAccount?.profile?.nickname}")
                            val rListener: Response.Listener<String?> = object : Response.Listener<String?> {

                                override fun onResponse(response: String?) {
                                    try {
                                        val jResponse = JSONObject(response!!.substring(response!!.indexOf("{"), response!!.lastIndexOf("}") + 1))
                                        val isExistWorker = jResponse.getBoolean("tryLogin")
                                        if (isExistWorker) {  // 회원이 존재하면 로그인된 화면으로 넘어감
                                            var worker_email1 = jResponse.getString("worker_email")
                                            var worker_name = jResponse.getString("worker_name")
                                            var password = jResponse.getString("worker_pw")
                                            var worker_gender = jResponse.getString("worker_gender")
                                            var worker_birth = jResponse.getString("worker_birth")
                                            var worker_phonenum = jResponse.getString("worker_phonenum")
                                            var worker_bankaccount = jResponse.getString("worker_bankaccount")
                                            var worker_bankname = jResponse.getString("worker_bankname")
                                            var worker_introduce = jResponse.getString("worker_introduce")
                                            var jobcode = jResponse.getString("jobcode")

                                            var hope_local_sido = jResponse.getString("hope_local_sido")
                                            var hope_local_sigugun = jResponse.getString("hope_local_sigugun")
                                            var jobcareer = jResponse.getString("jobcareer")


                                            Sharedpreference.set_Jobcareer(applicationContext(), "jobcareer", jobcareer)
                                            Sharedpreference.set_email(applicationContext(), "worker_email", worker_email1)
                                            Sharedpreference.set_Nickname(applicationContext(), "worker_name", worker_name)
                                            Sharedpreference.set_Password(applicationContext(), "worker_pw", password)
                                            Sharedpreference.set_Gender(applicationContext(), "worker_gender", worker_gender)
                                            Sharedpreference.set_Birth(applicationContext(), "worker_birth", worker_birth)
                                            Sharedpreference.set_Phonenum(applicationContext(), "worker_phonenum", worker_phonenum)
                                            Sharedpreference.set_Bankaccount(applicationContext(), "worker_bankaccount", worker_bankaccount)
                                            Sharedpreference.set_Bankname(applicationContext(), "worker_bankname", worker_bankname)
                                            Sharedpreference.set_introduce(applicationContext(), "worker_introduce", worker_introduce)
                                            Sharedpreference.set_Jobname(applicationContext(), "jobcode", jobcode)
                                            Sharedpreference.set_Hope_local_sido(applicationContext(), "hope_local_sido", hope_local_sido)
                                            Sharedpreference.set_Hope_local_sigugun(applicationContext(), "hope_local_sigugun", hope_local_sigugun)// 파일에 맵핑형식으로 저장

                                            Toast.makeText(this@MainActivity, "로그인에 성공하였습니다.", Toast.LENGTH_SHORT).show()
                                            intent() //
                                            //Toast.makeText(FindPasswordInfoActivity.this, "등록된 "+worker_pw, Toast.LENGTH_SHORT).show();
                                        } else {  // 회원이 존재하지 않는다면
                                            Sharedpreference.set_email(applicationContext(), "email", user.kakaoAccount?.email) // 이메일만 갖고온 후 나머지 정보는 회원가입 절차에서 입력

                                            signup(Sharedpreference.get_email(applicationContext, "email"), "1") // 회원가입 진행
                                        }
                                    } catch (e: Exception) {
                                        Log.d("mytest", e.toString())
                                    }

                                }
                            }
                            val lRequest = kr.co.ilg.activity.login.LoginRequest(user.kakaoAccount?.email, "1", rListener) // Request 처리 클래스
                            val queue = Volley.newRequestQueue(this) // 데이터 전송에 사용할 Volley의 큐 객체 생성

                            queue.add(lRequest) // Volley로 구현된 큐에 ValidateRequest 객체를 넣어둠으로써 실제로 서버 연동 발생
                        } else if (user.kakaoAccount?.emailNeedsAgreement == false) {
                            Log.e(TAG, "사용자 계정에 이메일 없음.")
                            val rListener: Response.Listener<String?> = object : Response.Listener<String?> {

                                override fun onResponse(response: String?) {
                                    try {
                                        val jResponse = JSONObject(response!!.substring(response!!.indexOf("{"), response!!.lastIndexOf("}") + 1))
                                        val isExistWorker = jResponse.getBoolean("tryLogin")
                                        if (isExistWorker) {  // 회원이 존재하면 로그인된 화면으로 넘어감
                                            var worker_email1 = jResponse.getString("worker_email")
                                            var worker_name = jResponse.getString("worker_name")
                                            var password = jResponse.getString("worker_pw")
                                            var worker_gender = jResponse.getString("worker_gender")
                                            var worker_birth = jResponse.getString("worker_birth")
                                            var worker_phonenum = jResponse.getString("worker_phonenum")
                                            var worker_bankaccount = jResponse.getString("worker_bankaccount")
                                            var worker_bankname = jResponse.getString("worker_bankname")
                                            var worker_introduce = jResponse.getString("worker_introduce")
                                            var jobcode = jResponse.getString("jobcode")
                                            var hope_local_sido = jResponse.getString("hope_local_sido")
                                            var hope_local_sigugun = jResponse.getString("hope_local_sigugun")
                                            var jobcareer = jResponse.getString("jobcareer")


                                            Sharedpreference.set_Jobcareer(applicationContext(), "jobcareer", jobcareer)

                                            Sharedpreference.set_email(applicationContext(), "worker_email", worker_email1)
                                            Sharedpreference.set_Nickname(applicationContext(), "worker_name", worker_name)
                                            Sharedpreference.set_Password(applicationContext(), "worker_pw", password)
                                            Sharedpreference.set_Gender(applicationContext(), "worker_gender", worker_gender)
                                            Sharedpreference.set_Birth(applicationContext(), "worker_birth", worker_birth)
                                            Sharedpreference.set_Phonenum(applicationContext(), "worker_phonenum", worker_phonenum)
                                            Sharedpreference.set_Bankaccount(applicationContext(), "worker_bankaccount", worker_bankaccount)
                                            Sharedpreference.set_Bankname(applicationContext(), "worker_bankname", worker_bankname)
                                            Sharedpreference.set_introduce(applicationContext(), "worker_introduce", worker_introduce)
                                            Sharedpreference.set_Jobname(applicationContext(), "jobcode", jobcode)
                                            Sharedpreference.set_Hope_local_sido(applicationContext(), "hope_local_sido", hope_local_sido)
                                            Sharedpreference.set_Hope_local_sigugun(applicationContext(), "hope_local_sigugun", hope_local_sigugun)// 파일에 맵핑형식으로 저장

                                            intent() //
                                            //Toast.makeText(FindPasswordInfoActivity.this, "등록된 "+worker_pw, Toast.LENGTH_SHORT).show();
                                        } else {  // 회원이 존재하지 않는다면
                                            Sharedpreference.set_email(applicationContext(), "worker_email", user.id.toString())

                                            signup(Sharedpreference.get_email(applicationContext, "worker_email"), "1") // 회원가입 진행
                                        }
                                    } catch (e: Exception) {
                                        Log.d("mytest", e.toString())
                                    }

                                }
                            }
                            val lRequest = kr.co.ilg.activity.login.LoginRequest(user.id.toString(), "1", rListener) // Request 처리 클래스
                            val queue = Volley.newRequestQueue(this) // 데이터 전송에 사용할 Volley의 큐 객체 생성

                            queue.add(lRequest) // Volley로 구현된 큐에 ValidateRequest 객체를 넣어둠으로써 실제로 서버 연동 발생
                        } else if (user.kakaoAccount?.emailNeedsAgreement == true) {
                            Log.d(TAG, "사용자에게 이메일 제공 동의를 받아야함.")

                            val scopes = listOf("account_email")
                            LoginClient.instance.loginWithNewScopes(this, scopes) { token, error ->
                                if (error != null) {
                                    Log.e(TAG, "이메일 제공 동의 실패", error)
                                } else {
                                    Log.d(TAG, "allowed scopes: ${token!!.scopes}")

                                    UserApiClient.instance.me { user, error ->
                                        if (error != null) {
                                            Log.e(TAG, "사용자 정보 요청 실패", error)
                                        } else if (user != null) {
                                            Log.i(TAG, "이메일: ${user.kakaoAccount?.email}" +
                                                    "\n닉네임: ${user.kakaoAccount?.profile?.nickname}")

                                            val rListener: Response.Listener<String?> = object : Response.Listener<String?> {

                                                override fun onResponse(response: String?) {
                                                    try {
                                                        val jResponse = JSONObject(response!!.substring(response!!.indexOf("{"), response!!.lastIndexOf("}") + 1))
                                                        val isExistWorker = jResponse.getBoolean("tryLogin")
                                                        if (isExistWorker) {  // 회원이 존재하면 로그인된 화면으로 넘어감
                                                            var worker_email1 = jResponse.getString("worker_email")
                                                            var worker_name = jResponse.getString("worker_name")
                                                            var password = jResponse.getString("worker_pw")
                                                            var worker_gender = jResponse.getString("worker_gender")
                                                            var worker_birth = jResponse.getString("worker_birth")
                                                            var worker_phonenum = jResponse.getString("worker_phonenum")
                                                            var worker_bankaccount = jResponse.getString("worker_bankaccount")
                                                            var worker_bankname = jResponse.getString("worker_bankname")
                                                            var worker_introduce = jResponse.getString("worker_introduce")
                                                            var jobcode = jResponse.getString("jobcode")
                                                            var hope_local_sido = jResponse.getString("hope_local_sido")
                                                            var hope_local_sigugun = jResponse.getString("hope_local_sigugun")
                                                            var jobcareer = jResponse.getString("jobcareer")


                                                            Sharedpreference.set_Jobcareer(applicationContext(), "jobcareer", jobcareer)

                                                            Sharedpreference.set_email(applicationContext(), "worker_email", worker_email1)
                                                            Sharedpreference.set_Nickname(applicationContext(), "worker_name", worker_name)
                                                            Sharedpreference.set_Password(applicationContext(), "worker_pw", password)
                                                            Sharedpreference.set_Gender(applicationContext(), "worker_gender", worker_gender)
                                                            Sharedpreference.set_Birth(applicationContext(), "worker_birth", worker_birth)
                                                            Sharedpreference.set_Phonenum(applicationContext(), "worker_phonenum", worker_phonenum)
                                                            Sharedpreference.set_Bankaccount(applicationContext(), "worker_bankaccount", worker_bankaccount)
                                                            Sharedpreference.set_Bankname(applicationContext(), "worker_bankname", worker_bankname)
                                                            Sharedpreference.set_introduce(applicationContext(), "worker_introduce", worker_introduce)
                                                            Sharedpreference.set_Jobname(applicationContext(), "jobcode", jobcode)
                                                            Sharedpreference.set_Hope_local_sido(applicationContext(), "hope_local_sido", hope_local_sido)
                                                            Sharedpreference.set_Hope_local_sigugun(applicationContext(), "hope_local_sigugun", hope_local_sigugun)// 파일에 맵핑형식으로 저장

                                                            intent() //
                                                            //Toast.makeText(FindPasswordInfoActivity.this, "등록된 "+worker_pw, Toast.LENGTH_SHORT).show();
                                                        } else {  // 회원이 존재하지 않는다면
                                                            Sharedpreference.set_email(applicationContext(), "worker_email", user.kakaoAccount?.email) // 이메일만 갖고온 후 나머지 정보는 회원가입 절차에서 입력

                                                            signup(Sharedpreference.get_email(applicationContext, "worker_email"), "1") // 회원가입 진행
                                                        }
                                                    } catch (e: Exception) {
                                                        Log.d("mytest", e.toString())
                                                    }

                                                }
                                            }
                                            val lRequest = kr.co.ilg.activity.login.LoginRequest(user.kakaoAccount?.email, "1", rListener) // Request 처리 클래스
                                            val queue = Volley.newRequestQueue(this) // 데이터 전송에 사용할 Volley의 큐 객체 생성

                                            queue.add(lRequest) // Volley로 구현된 큐에 ValidateRequest 객체를 넣어둠으로써 실제로 서버 연동 발생
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        kakaoLoginBtn.setOnClickListener {
            if (LoginClient.instance.isKakaoTalkLoginAvailable(this)) {
                LoginClient.instance.loginWithKakaoTalk(this, callback = callback)
            } else {
                LoginClient.instance.loginWithKakaoAccount(this, callback = callback)
            }
        }

        loginBtn.setOnClickListener {
            val worker_email: String = idET.getText().toString()
            val worker_pw: String = pwET.getText().toString()
            val rListener: Response.Listener<String?> = object : Response.Listener<String?> {

                override fun onResponse(response: String?) {
                    try {
                        val jResponse = JSONObject(response!!.substring(response!!.indexOf("{"), response!!.lastIndexOf("}") + 1))
                        var jobname = Array<String>(3) { "" }
                        var jobcareer = Array<String>(3) { "" }
                        var a = jResponse.getJSONObject("response")
                        val isExistWorker = a.getBoolean("tryLogin")
                        println(isExistWorker)
                        if (isExistWorker) {  // 회원이 존재하면 로그인된 화면으로 넘어감
                            var worker_email = a.getString("worker_email")
                            var worker_name = a.getString("worker_name")
                            var password = a.getString("worker_pw")
                            var worker_gender = a.getString("worker_gender")
                            var worker_birth = a.getString("worker_birth")
                            var worker_phonenum = a.getString("worker_phonenum")
                            var worker_bankaccount = a.getString("worker_bankaccount")
                            var worker_bankname = a.getString("worker_bankname")
                            var worker_introduce = a.getString("worker_introduce") ///////  여기까지 값들어
                            var local_sido = a.getString("local_sido")
                            var local_sigugun = a.getString("local_sigugun")


                            var k = arrayOf("0", "1", "2")
                            for (i in 0 until a.length()-12) {
                                var s = a.getJSONObject(k[i])
                                jobname[i] = s.getString("jobname")
                                jobcareer[i] = s.getString("jobcareer")
                            } ///실행되다가





                            Sharedpreference.set_Jobcareer(applicationContext(), "jobname1", jobname[0])
                            Sharedpreference.set_Jobcareer(applicationContext(), "jobname2", jobname[1])
                            Sharedpreference.set_Jobcareer(applicationContext(), "jobname3", jobname[2])

                            Sharedpreference.set_Jobname(applicationContext(), "jobcareer1", jobcareer[0])
                            Sharedpreference.set_Jobname(applicationContext(), "jobcareer2", jobcareer[1])
                            Sharedpreference.set_Jobname(applicationContext(), "jobcareer3", jobcareer[2])


                            Sharedpreference.set_email(applicationContext(), "worker_email", worker_email)
                            Sharedpreference.set_Nickname(applicationContext(), "worker_name", worker_name)
                            Sharedpreference.set_Password(applicationContext(), "worker_pw", password)
                            Sharedpreference.set_Gender(applicationContext(), "worker_gender", worker_gender)
                            Sharedpreference.set_Birth(applicationContext(), "worker_birth", worker_birth)
                            Sharedpreference.set_Phonenum(applicationContext(), "worker_phonenum", worker_phonenum)
                            Sharedpreference.set_Bankaccount(applicationContext(), "worker_bankaccount", worker_bankaccount)
                            Sharedpreference.set_Bankname(applicationContext(), "worker_bankname", worker_bankname)
                            Sharedpreference.set_introduce(applicationContext(), "worker_introduce", worker_introduce)

                            Sharedpreference.set_Hope_local_sido(applicationContext(), "local_sido", local_sido)
                            Sharedpreference.set_Hope_local_sigugun(applicationContext(), "local_sigugun", local_sigugun)// 파일에 맵핑형식으로 저장

                            intent() //
                            //Toast.makeText(FindPasswordInfoActivity.this, "등록된 "+worker_pw, Toast.LENGTH_SHORT).show();
                        } else {  // 회원이 존재하지 않는다면
                            Toast.makeText(this@MainActivity, "로그인실패", Toast.LENGTH_SHORT).show();
                        }
                    } catch (e: Exception) {
                        Log.d("mytest", e.toString()) // 오류 출력
                    }

                }
            }
            val lRequest = kr.co.ilg.activity.login.LoginRequest(worker_email, worker_pw, rListener) // Request 처리 클래스
            val queue = Volley.newRequestQueue(this) // 데이터 전송에 사용할 Volley의 큐 객체 생성

            queue.add(lRequest) // Volley로 구현된 큐에 ValidateRequest 객체를 넣어둠으로써 실제로 서버 연동 발생
        }
        findPwBtn.setOnClickListener {
            val intent = Intent(this, FindPasswordInfoActivity::class.java)
            startActivity(intent)
        }

        signUpBtn.setOnClickListener {
            val intent = Intent(this, SignupEmailActivity::class.java)
            startActivity(intent)
        }
    }


}


