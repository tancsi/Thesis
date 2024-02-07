package com.github.pires.obd.reader.config;

import android.content.Context;
import android.os.AsyncTask;

import java.net.HttpURLConnection;
import java.net.URL;
import java.net.URLEncoder;

public class UpdateTripNumTask extends AsyncTask<String, String, String> {
    private Context context;
    public UpdateTripNumTask(Context context){
        this.context=context;
    }

    @Override
    protected String doInBackground(String... strings) {
        try {
            String encodedStudentId = URLEncoder.encode(strings[0].split("_")[0], "UTF-8");
            URL url = new URL("http://obd.narasoft.hu/update_trips_num.php?student_id=" + encodedStudentId);
            HttpURLConnection conn = (HttpURLConnection) url.openConnection();
            int responseCode = conn.getResponseCode();
            conn.disconnect();
        } catch (Exception e) {
            e.printStackTrace();
        }
        return null;
    }
}
