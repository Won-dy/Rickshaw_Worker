package kr.co.ilg.activity.findwork;

import com.android.volley.AuthFailureError;
import com.android.volley.Response;
import com.android.volley.toolbox.StringRequest;

import java.lang.reflect.Method;
import java.util.HashMap;
import java.util.Map;

public class GoneFieldRequest extends StringRequest {
    final static private String URL = "http://14.63.220.50/GoneFieldRequest.php";

    private Map<String, String> parameters;  // 전송 데이터 넣을 Map 객체 선언

    public GoneFieldRequest(String worker_email,String phpdate,String phpdate1, Response.Listener<String> listener) {  // 서버에 전송될 data, 응답(결과) 처리하는 리스너
        super(Method.POST, URL, listener, null);  // 가독성 향상을 위해 super에 선언

        // HashMap으로 데이터 정의하고 추가
        parameters = new HashMap<>();
        parameters.put("worker_email", worker_email);
        parameters.put("phpdate", phpdate);
        parameters.put("phpdate1", phpdate1);

    }

    // getParams 재정의
    protected Map<String, String> getParams() throws AuthFailureError {
        // 서버에 전송할 문자 데이터들을 Map 객체로 리턴
        return parameters;
    }
}
