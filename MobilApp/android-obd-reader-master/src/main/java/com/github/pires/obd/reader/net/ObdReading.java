/*
 * Licensed under the Apache License, Version 2.0 (the "License"); you may not
 * use this file except in compliance with the License. You may obtain a copy of
 * the License at
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations under
 * the License.
 */

package com.github.pires.obd.reader.net;

import java.util.HashMap;
import java.util.Map;

/**
 * DTO for OBD readings.
 */
public class ObdReading {
    private double latitude, longitude, altitude;
    private long timestamp;
    private String vin; // vehicle id
    private Map<String, String> readings;
    private int trip_num;
    private String teacher_id;

    public ObdReading() {
        readings = new HashMap<>();
    }

    public ObdReading(double latitude, double longitude, double altitude, long timestamp,
                      String vin, Map<String, String> readings, int trip_num, String teacher_id){
        this.latitude = latitude;
        this.longitude = longitude;
        this.altitude = altitude;
        this.timestamp = timestamp;
        this.vin = vin;
        this.readings = readings;
        this.trip_num = trip_num;
        this.teacher_id= teacher_id;
    }

    public double getLatitude() {
        return latitude;
    }

    public void setLatitude(double latitude) {
        this.latitude = latitude;
    }

    public double getAltitude() {
        return altitude;
    }

    public void setAltitude(double altitude) {
        this.altitude = altitude;
    }

    public double getLongitude() {
        return longitude;
    }

    public void setLongitude(double longitude) {
        this.longitude = longitude;
    }

    public long getTimestamp() {
        return timestamp;
    }

    public void setTimestamp(long timestamp) {
        this.timestamp = timestamp;
    }

    public String getVin() {
        return vin;
    }

    public void setVin(String vehicleid) {
        this.vin = vehicleid;
    }

    public Map<String, String> getReadings() {
        return readings;
    }

    public void setReadings(Map<String, String> readings) {
        this.readings = readings;
    }

    public int getTripNum() {
        return trip_num;
    }

    public void setTripNum(int trip_num) {
        this.trip_num=trip_num;
    }

    public String getTeacherID() {
        return teacher_id;
    }

    public void setTeacherID(String teacher_id) {
        this.teacher_id=teacher_id;
    }
    public String toString() {

        return "lat:" + latitude + ";" +
                "long:" + longitude + ";" +
                "alt:" + altitude + ";" +
                "vin:" + vin + ";" +
                "readings:" + readings.toString().substring(10).replace("}", "").replace(",", ";")+
                "trip_num: "+trip_num+ ";"+
                "teacher_id: "+teacher_id+";";
    }

}
