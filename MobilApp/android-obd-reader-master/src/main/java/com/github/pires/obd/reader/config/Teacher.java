package com.github.pires.obd.reader.config;

public class Teacher {
    private String teacherID;
    private String firstName;
    private String lastName;
    private String userName;

    public Teacher(String teacherID, String firstName, String lastName,String userName) {
        this.teacherID = teacherID;
        this.firstName = firstName;
        this.lastName = lastName;
        this.userName = userName;
    }

    public String getTeacherID() {
        return teacherID;
    }

    public String getFirstName() {
        return firstName;
    }

    public String getLastName() {
        return lastName;
    }

    public String getUserName() {
        return userName;
    }
}
