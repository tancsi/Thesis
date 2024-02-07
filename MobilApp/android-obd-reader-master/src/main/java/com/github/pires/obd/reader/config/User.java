package com.github.pires.obd.reader.config;

public class User {
    private String userId;
    private String firstName;
    private String lastName;
    private int totalTrips;

    public User(String userId, String firstName, String lastName,int totalTrips) {
        this.userId = userId;
        this.firstName = firstName;
        this.lastName = lastName;
        this.totalTrips = totalTrips;
    }

    public String getUserId() {
        return userId;
    }

    public String getFirstName() {
        return firstName;
    }

    public String getLastName() {
        return lastName;
    }
    public int getTotalTrips() {
        return totalTrips;
    }
}
