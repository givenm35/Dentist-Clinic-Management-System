# Dentist-Clinic-Management-System

In this project, a database management system is designed to manage information for a dentist clinic. The database is stored in a MySQL server using phpMyAdmin as the administration tool. The user interface system was developed using PHP, HTML, and JavaScript.
There are 8 tables in the database. These are: appointments, users, clients, employees, medecines, patient_medecine, products and orders. 
An employee has the role of either admin, employee, dentist or nurse. Their login information is stored in the users table whilst their other metadata are stored in the employees table. 

Apart from offering clinical services, the clinic also sells some dental products. Therefore, a clients table was chosen to avoid distinction between patients and customers because they both can buy products the clinic sells and also receive treatment from the clinic. 
In this system, dentists attend to more than one patient but a patient can only see one dentist. However, it is also assumed that a patient can have more than one appointment scheduled with their dentist. 

The developed system is also a role-based system. Therefore, what a user can do is dependent on their assigned role. In this case, admins have access to manage all the tables. Employees can also manage all tables apart from the users table. Dentists can view and manage medecines and appointments. Nurses can only view medecines and appointments and cannot modify them in any way.
