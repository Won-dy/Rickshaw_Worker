package kr.co.ilg.activity.findwork;

import android.content.Intent;
import android.os.Bundle;
import android.util.Log;
import android.widget.TextView;
import android.widget.Toast;

import androidx.annotation.Nullable;
import androidx.appcompat.app.AppCompatActivity;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;

import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.toolbox.Volley;
import com.example.capstone.R;

import org.json.JSONArray;
import org.json.JSONObject;

import java.util.ArrayList;

import kr.co.ilg.activity.mypage.ReviewManageActivity;
import kr.co.ilg.activity.mypage.getReviewRequest;
import kr.co.ilg.activity.mypage.mypagereviewAdapter;
import kr.co.ilg.activity.mypage.mypagereviewitem;

public class OfficeInfoActivity extends AppCompatActivity {
    TextView office_name, office_address, office_manager_name, office_manager_tel, office_tel, office_introduce;
    RecyclerView review_RecyclerView;
    ReviewAdapter myAdapter;
    Response.Listener aListener;
    String officename;
    int k;
    String name[], contents[],datetime[],key[];
    RecyclerView.LayoutManager layoutManager;
    @Override
    protected void onCreate(@Nullable Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.office_info);

        Intent receiver = getIntent();
        String business_reg_num = receiver.getExtras().getString("business_reg_num");

        //Toast.makeText(getApplicationContext(), business_reg_num, Toast.LENGTH_SHORT).show();
        office_introduce=findViewById(R.id.office_introduce);
        office_name=findViewById(R.id.office_name);
        office_address=findViewById(R.id.office_address);
        office_manager_name=findViewById(R.id.office_manager_name);
        office_manager_tel=findViewById(R.id.office_manager_tel);
        office_tel=findViewById(R.id.office_tel);
        review_RecyclerView = findViewById(R.id.review_list);

        Response.Listener rListener = new Response.Listener<String>() {
            @Override
            public void onResponse(String response) {
                try {
                    JSONObject jResponse = new JSONObject(response.substring(response.indexOf("{"), response.lastIndexOf("}") + 1));
                    boolean select_Mng = jResponse.getBoolean("select_Mng");
                    if (select_Mng) {
                        officename=jResponse.getString("manager_office_name");
                        office_name.setText(jResponse.getString("manager_office_name"));
                        office_tel.setText(jResponse.getString("manager_office_telnum"));
                        office_address.setText(jResponse.getString("manager_office_address"));
                        office_manager_name.setText(jResponse.getString("manager_name"));
                        office_manager_tel.setText(jResponse.getString("manager_phonenum"));
                        office_introduce.setText(jResponse.getString("manager_office_info"));
                        //office_introduce.setText("안녕하세요 ★개미인력★입니다.\n현재 마포구 일대의 보내는 인력만 20명이 넘습니다.\n단가는 평균 15만원에서 16만원 정도이구요,\n초보 경력 상관없이 일자리 많으니까 많은 지원부탁드립니다!");
                    } else {
                        Toast.makeText(getApplicationContext(), "사무소 정보 로드 실패", Toast.LENGTH_SHORT).show();
                    }
                } catch (Exception e) {
                    Log.d("mytest", e.toString());
                }
            }
        };
        OfficeInfoSelectRequest oisRequest = new OfficeInfoSelectRequest(business_reg_num, rListener);

        RequestQueue queue = Volley.newRequestQueue(OfficeInfoActivity.this);
        queue.add(oisRequest);

        aListener = new Response.Listener<String>() {
            @Override
            public void onResponse(String response) {

                try {
                    Log.d("ttttttttttttttt","true");

                    JSONObject jResponse = new JSONObject(response.substring(response.indexOf("{"), response.lastIndexOf("}") + 1));
                    JSONArray array = jResponse.getJSONArray("response");
                    k = array.length();
                    name = new String[k];
                    contents = new String[k];
                    datetime = new String[k];

                    final ArrayList<ReviewItem> reviewList=new ArrayList<>();

                    for (int i = 0; i < array.length(); i++) {
                        JSONObject MainRequest = array.getJSONObject(i);
                        name[i] = MainRequest.getString("name");
                        contents[i] = MainRequest.getString("contents");
                        datetime[i] = MainRequest.getString("datetime");
                        reviewList.add(new ReviewItem(name[i], contents[i], datetime[i]));
                    } // 값넣기*/
                    myAdapter = new ReviewAdapter(reviewList);
                    review_RecyclerView.setAdapter(myAdapter);


                } catch (Exception e) {
                    Log.d("mytest", e.toString());
                }
            }
        };
        getReviewRequest mainRequest = new getReviewRequest(business_reg_num, 1 , aListener);  // Request 처리 클래스

        RequestQueue queue1 = Volley.newRequestQueue(OfficeInfoActivity.this);  // 데이터 전송에 사용할 Volley의 큐 객체 생성
        queue1.add(mainRequest);

        review_RecyclerView = findViewById(R.id.review_list);
        review_RecyclerView.setHasFixedSize(true);
        layoutManager=new LinearLayoutManager(this);
        review_RecyclerView.setLayoutManager(layoutManager);

    }
}
