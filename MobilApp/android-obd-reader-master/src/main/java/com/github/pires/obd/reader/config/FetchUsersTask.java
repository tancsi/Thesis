package com.github.pires.obd.reader.config;

import android.content.Context;
import android.os.AsyncTask;

import com.github.pires.obd.reader.activity.ConfigActivity;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.io.BufferedReader;
import java.io.InputStreamReader;
import java.net.HttpURLConnection;
import java.net.URL;
import java.util.ArrayList;



public class FetchUsersTask extends AsyncTask<Void, Void, String> {
    private FetchUsersCallback callback; // Reference to the callback interface
    private ConfigActivity activity;

    public FetchUsersTask(Context context) {
        this.callback = (FetchUsersCallback) context;
        this.activity=(ConfigActivity) context;
    }
    @Override
    protected String  doInBackground(Void... voids) {
        String result = "";
        try {
            URL url = new URL("http://obd.narasoft.hu/usersquery.php");
            HttpURLConnection conn = (HttpURLConnection) url.openConnection();

            BufferedReader reader = new BufferedReader(new InputStreamReader(conn.getInputStream()));
            String line;
            while ((line = reader.readLine()) != null) {
                result += line;
            }
            reader.close();
            conn.disconnect();
        } catch (Exception e) {
            e.printStackTrace();
        }
        return result;
    }

    @Override
    protected void onPostExecute(String result) {
        ArrayList<User> usersList = new ArrayList<>();
        try {
            JSONArray jsonArray = new JSONArray(result);
            for (int i = 0; i < jsonArray.length(); i++) {
                JSONObject jsonObject = jsonArray.getJSONObject(i);
                String userId = jsonObject.getString("user_id");
                String firstName = jsonObject.getString("first_name");
                String lastName = jsonObject.getString("last_name");
                int totalTrips= jsonObject.getInt("total_trips");
                usersList.add(new User(userId, firstName, lastName, totalTrips));
            }
        } catch (JSONException e) {
            e.printStackTrace();
        }

        // Pass the usersList to the callback
        if (callback != null) {
            callback.setResults(usersList);
        }
    }
}
