package kr.co.ilg.activity.findwork;

import com.android.volley.AuthFailureError;
import com.android.volley.Response;
import com.android.volley.toolbox.StringRequest;

import java.util.HashMap;
import java.util.Map;

public class MapForFieldRequest extends StringRequest {
    final static private String URL ="http://14.63.220.50/MapForField.php";
    private Map<String, String> parameters;


    public MapForFieldRequest(String field_code, Response.Listener<String> listener){

        super(Method.POST, URL, listener, null); //super로 가독성을 업!


        //데이터들
        parameters = new HashMap<>();
        parameters.put("field_code", field_code); //데이터 넣기  ≒ putextra
    }


    //서버에 전송할 데이터를 Map 객체로 리턴
    //서버에 요청할 때 앱의 문자열 데이터를 Map 객체로 리턴하여 전달한다
    @Override
    protected Map<String, String> getParams() throws AuthFailureError {
        return parameters;
    }
}

