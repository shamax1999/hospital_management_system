

CREATE TABLE accountant(
accountant_id int (11) NOT NULL,
name varchar (50) NOT NULL,
email varchar (25) NOT NULL,
password varchar (25) NOT NULL,
address varchar (50) NOT NULL,
phone varchar (15) NOT NULL);

INSERT INTO accountant(accountant_id,name,email,password,address,phone) VALUES
(1, 'Nihal Siriwardhana', 'nihal@gmail.com', 'nihal56', '5A Gamhatha Weligalla Gampola', '0774327934'),
(2, 'Ravindra Senavirathna', 'ravi@gmail.com', 'ravi89', '128 Makadura Mathara', '0701568810'),
(3, 'Ruvini Ganemulla', 'ruvini@gmail.com', 'ruvini55', '67 Kothmale Nawalapitiya', '0776618446');


CREATE TABLE admin(
admin_id int (11) NOT NULL,
name varchar (50) NOT NULL,
email varchar (25) NOT NULL,
password varchar (25) NOT NULL,
address varchar (50) NOT NULL,
phone varchar (15) NOT NULL);

INSERT INTO admin(admin_id,name,email,password,address,phone) VALUES
(1, 'Channa Dias', 'channa@gmail.com', 'channa24', '66 Beddagana kotte', '0773579240');


CREATE TABLE appointment(
  appointment_id int(11) NOT NULL,
  appointment_timestamp int(11) NOT NULL,
  doctor_id int(11) NOT NULL,
  patient_id int(11) NOT NULL);
  
INSERT INTO appointment(appointment_id,appointment_timestamp,doctor_id,patient_id) VALUES
(7, 1651183200, 8, 8),
(6, 1651096800, 11, 6),
(5, 1651096800, 8, 5);


CREATE TABLE bed(
bed_id int (11) NOT NULL,
bed_number varchar (25) NOT NULL,
type varchar (10) NOT NULL COMMENT 'ward,cabin,ICU',
status int (11) NOT NULL DEFAULT '0',
description varchar(255) NOT NULL);

INSERT INTO bed(bed_id,bed_number,type,status,description) VALUES
(1, 'W1', 'ward', 0, 'Ward Number 1'),
(2, 'W2', 'ward', 0, 'Ward Number 2'),
(3, 'ICU1', 'icu', 0, 'ICU 1'),
(4, 'CB1', 'cabin', 0, 'Cabin Number 1');


CREATE TABLE bed_allotment(
bed_allotment_id int (11) NOT NULL,
bed_id int (11) NOT NULL,
patient_id int (11) NOT NULL,
allotment_timestamp date NOT NULL,
discharge_timestamp date NOT NULL);

INSERT INTO bed_allotment(bed_allotment_id,bed_id,patient_id,allotment_timestamp,discharge_timestamp) VALUES
(7, 2, 6, '2022-04-29', '2022-05-11'),
(6, 1, 5, '2022-04-28', '2022-05-02');

CREATE TABLE blood_donor(
blood_donor_id int (11) NOT NULL,
name varchar (50) NOT NULL,
blood_group varchar (5) NOT NULL,
sex varchar (10) NOT NULL,
age int (11) NOT NULL,
phone varchar (15) NOT NULL,
email varchar (25) NOT NULL,
address varchar (50) NOT NULL,
last_donation_timestamp int (11) NOT NULL);


INSERT INTO blood_donor(blood_donor_id,name,blood_group,sex,age,phone,email,address,last_donation_timestamp) VALUES
(1, 'Shiwantha Dinesh', 'A+', 'male', 25, '0772827942', 'shiwantha@gmail.com', '72 Horana Colombo', 1413237600),
(2, 'Milindu Chaman', 'B-', 'male', 29, '0756222456', 'milindu@gmail.com', '45 Galkissa Colombo', 1650924000);


CREATE TABLE department(
department_id int (11) NOT NULL,
name varchar (50) NOT NULL,
description varchar (500) NOT NULL);


INSERT INTO department(department_id,name,description) VALUES
(1, 'Allergists', 'An allergist or immunologist focuses on preventing and treating allergic diseases and conditions. These usually include various types of allergies and asthma.'),
(2, 'Bacteriological Laboratory', 'Bacteriological Laboratory'),
(3, 'Cardiac Surgeons', 'Cardiac surgeons perform heart surgery and may work with a cardiologist to determine what a person needs.'),
(4, 'Neurologists', 'A neurologist treats conditions of the nerves, spine, and brain.'),
(5, 'Infectious disease doctors', 'Infectious disease doctors specialize in diseases and conditions that are contagious. Includes: influenza, stomach issues, hiv, pneumonia, tuberclosis'),
(6, 'Pulmonologists', 'Pulmonologists focus on the organs involved with breathing. These include the lungs and heart.'),
(7, 'Nephrologists', 'A nephrologist focuses on kidney care and conditions that affect the kidneys.'),
(8, 'Ophthalmologists', 'Ophthalmologists specialize in eye and vision care. They treat diseases and conditions of the eyes and can perform eye surgery.'),
(9, 'Obstetrician/Gynecologists', 'For female health conditions: female reproductive health, cancer prevention and diagnosis in the female reproductive organs, breast care, pregnancy, labor and delivery, infertility, menopause'),
(10, 'Cardiologists', 'Cardiologists focus on the cardiovascular system, which includes the heart and blood vessels. high blood pressure, high cholesterol, heart attack and stroke'),
(11, 'Endocrinologists', 'Endocrinologists treat hormone-related conditions such as: diabetes, thyroid conditions, hormone imbalances, infertility, growth problems in children'),
(12, 'Gastroenterologists', 'Gastroenterologists focus on the digestive system. This includes the esophagus, pancreas, stomach, liver, small intestine, colon, and gallbladder.'),
(13, 'Physical Therapy', 'Physical Therapy'),
(14, 'Urologists', 'Urologists treat conditions of the urinary tract in both males and females.'),
(15, 'Anesthesiology', 'Anesthesiology'),
(16, 'Otolaryngologists', 'An ENT doctor may treat problems with the sinuses, throat, tonsils, ears, mouth, head, and neck.'),
(17, 'Dermatologists', 'Dermatologists focus on diseases and conditions of the skin, nails, and hair. They treat conditions such as eczema, skin cancer, acne, and psoriasis.'),
(18, 'Psychiatrists', 'A psychiatrist is a doctor who treats mental health conditions. They may use counseling, medication, or hospitalization as part of their treatment.'),
(19, 'Oncologists', 'Oncologists treat cancer and its symptoms. During treatment for cancer, a person may have several types of healthcare professional in their care team.'),
(20, 'Radiologists', 'A radiologist specializes in diagnosing and treating conditions using medical imaging tests. They may read and interpret scans such as X-rays, MRIs, mammograms, ultrasound, and CT scans.'),
(21, 'General Surgeons', 'General surgeons perform surgical procedures on many organs and bodily systems. '),
(22, 'Orthopedic Surgeons', 'An orthopedic surgeon specializes in diseases and conditions of the bones, muscles, ligaments, tendons, and joints.'),
(23, 'Plastic Surgery', 'Plastic Surgery');


CREATE TABLE diagnosis_report(
diagnosis_report_id int (11) NOT NULL,
report_type varchar (15) NOT NULL COMMENT 'xray,blood test',
document_type varchar (15) NOT NULL COMMENT 'text,photo',
file_name varchar (15) NOT NULL,
prescription_id int (11) NOT NULL,
description varchar (1500) NOT NULL,
timestamp int (11) NOT NULL,
laboratorist_id int(11) NOT NULL);


INSERT INTO diagnosis_report(diagnosis_report_id,report_type,document_type,file_name,prescription_id,description,timestamp,laboratorist_id) VALUES
(3, 'X-Ray', 'image', 'x-ray.jpg', 4, 'Cardiologists test', 1651168181, 6);


CREATE TABLE doctor(
doctor_id int (11) NOT NULL,
name varchar (50) NOT NULL,
email varchar (25) NOT NULL,
password varchar (25) NOT NULL,
address varchar (50) NOT NULL,
phone varchar (15) NOT NULL,
department_id int (11) NOT NULL,
profile varchar (500) NOT NULL);


INSERT INTO doctor(doctor_id,name,email,password,address,phone,department_id,profile) VALUES
(1, 'Ashan Sajitha', 'ashan@gmail.com', 'ashan198', '134 Wewalwala Galle', '0771234944', 4, 'ashan'),
(2, 'Thiwanka Alahakoon', 'thiwanka@gmail.com', 'thiwanka95', '7 Pilimathalawa Kandy', '0778933766', 3, 'thiwanka'),
(3, 'Nadun Yalegama', 'nadun@gmail.com', 'nadun94', '24 Sinhapitiya Gampola', '0773162278', 18, 'nadun'),
(4, 'Isuri Devindi', 'isuri@gmail.com', 'isuri99', '5 Alkaduwa Mathale', '0708924288', 5, 'isuri'),
(5, 'Janith Wijebanadara', 'janith@gmail.com', 'janith00', '9 Kagalle', '0756728199', 6, 'janith'),
(6, 'Kamal Perera', 'Kamal64@gmail.com', 'kamal64', '5B Weligalle Gampola', '0779894761', 8, 'kamal'),
(7, 'Sanduni Nimesha', 'sanduni75@gmail.com', 'sanduni75', '60B Makadura Mathara', '0751290034', 9, 'sanduni'),
(8, 'Bihesha Disanayake', 'bihesha96@gmail.com', 'bihesha88', '9A Kappetipola Nuwaraeliya', '0779012734', 10, 'bihesha'),
(9, 'Chamalka Herath', 'chamalka91@gmail.com', 'chamalka67', '4B Mampitiya Galle', '0762941598', 11, 'chamalka'),
(10, 'Ishan Omantha', 'ishan04@gmail.com', 'ishan77', '20 Kandalama Mirigama', '0762459225', 12, 'ishan'),
(11, 'Dilshan Chandrasekara', 'dilshanc95@gmail.com', 'dilshan55', '55/C Ambepussa Warakapola', '0812345919', 13, 'dishan'),
(12, 'Sandamini Kumari', 'sandamini97@gmail.com', 'sanda90', '77 Malsiripura Uthura', '0778828829', 14, 'sandamini'),
(13, 'Gishan Dias', 'gishan82@gmail.com', 'gishan55', '4/A Kothmale Nawalapitiya', '0775624678', 20, 'gishan');


CREATE TABLE email_template(
email_template_id int (11) NOT NULL,
task varchar (50) NOT NULL,
subject varchar(50) NOT NULL,
body varchar(500) NOT NULL);


CREATE TABLE invoice(
invoice_id int (11) NOT NULL,
patient_id int (11) NOT NULL,
title varchar (100) NOT NULL,
description varchar (500) NOT NULL,
amount int (11) NOT NULL,
creation_timestamp int (11) NOT NULL,
status varchar (100) NOT NULL COMMENT 'paid or unpaid');


INSERT INTO invoice(invoice_id,patient_id,title,description,amount,creation_timestamp,status) VALUES
(4, 6, 'Threatment', 'threatment', 2500, 1651170171, 'paid'),
(3, 5, 'Number booking', 'booking charge', 500, 1651162669, 'paid');


CREATE TABLE laboratorist(
laboratorist_id int (11) NOT NULL,
name varchar (50) NOT NULL,
email varchar (25) NOT NULL,
password varchar(25) NOT NULL,
address varchar (50) NOT NULL,
phone varchar (15) NOT NULL);


INSERT INTO laboratorist(laboratorist_id,name,email,password,address,phone) VALUES
(1, 'Akila Ferando', 'akila@gmail.com', 'akila76', '9 yakkamulla Galle', '0712784366'),
(2, 'Gayan Sandakalum', 'gayan@gmail.com', 'gayan88', '2A Thiththapajjala Nugawela', '0709149285'),
(3, 'Ishini Tennakoon', 'ishini@gmail.com', 'ishini53', '15 Waththegama Kandy', '0723678356'),
(4, 'Ruwani Gunarathna', 'ruvani@gmail.com', 'ruwani90', '7B Dikoya Hatton', '0777894469'),
(5, 'Sandeepa Srimal', 'sandeepa@gmail.com', 'sandeepa97', '251 Katunayaka Road Migamuwa', '0779016844'),
(6, 'Chalith Sillara', 'chalith@gmail.com', 'chalith96', '17A Mountbrich Trinco', '0701689955');


CREATE TABLE log(
log_id int (11) NOT NULL,
type varchar (100) NOT NULL,
timestamp int (11) NOT NULL,
user_type int (11) NOT NULL,
user_id int (11) NOT NULL,
description varchar (500) NOT NULL,
ip varchar (100) NOT NULL,
location varchar(250) NOT NULL);


CREATE TABLE message(
message_id int (11) NOT NULL,
message varchar (1000) NOT NULL,
user_from_type varchar (200) NOT NULL,
user_from_id int (11) NOT NULL,
user_to_type varchar (100) NOT NULL,
user_type_id int (11) NOT NULL,
timestamp varchar(100) NOT NULL);


CREATE TABLE noticeboard(
notice_id int (11) NOT NULL,
notice_title varchar (100) NOT NULL,
notice varchar (1000) NOT NULL,
create_timestamp int (11) NOT NULL);


INSERT INTO noticeboard(notice_id,notice_title,notice,create_timestamp) VALUES
(1, 'COVID 19 Vaccine', 'Covid 19 vaccines available.Every Friday and Sunday you can get', 1647298800),
(2, 'Blood Donate Camp', 'We have organized a blood donation camp on 25th of August in 2022', 1648764000),
(3, 'Physical Therapy', 'Physical therapies are delayed until futher notice', 1651183200);


CREATE TABLE nurse(
nurse_id int (11) NOT NULL,
name varchar (50) NOT NULL,
email varchar (25) NOT NULL,
password varchar (25) NOT NULL,
address varchar (50) NOT NULL,
phone varchar (15) NOT NULL);


INSERT INTO nurse(nurse_id,name,email,password,address,phone) VALUES
(1, 'Ashini Sajana', 'ashi@gmail.com', 'ashi98', '13 Wallawaththa Colombo', '0771734944'),
(2, 'Thiwanthi Feranando', 'thiwanthi@gmail.com', 'thiwanthi98', '127 Pilimathalawa Kandy', '0778933766'),
(3, 'Naduni Bandara', 'naduni@gmail.com', 'naduni90', '68 Wigula road Gampola', '0773162278'),
(4, 'Isuru Senavirathna', 'isuru@gmail.com', 'isuru99', '5 Balapitiya Galle', '0700024288'),
(5, 'Janani Bandara', 'janani@gmail.com', 'janani45', '9 Buttala Monaragala', '0756888199'),
(6, 'Kamani Perera', 'Kamani68@gmail.com', 'kamani68', '5B Kinniya Trinkomalee', '0779894771'),
(7, 'Sandun Nimesh', 'sandun57@gmail.com', 'sandun57', '6B Makadura Mathara', '0751290034'),
(8, 'Bihesha Bandara', 'bandara96@gmail.com', 'bandara88', '9A Naula Mathale', '0779033734'),
(9, 'Chamalka Kavindi', 'chamalka98@gmail.com', 'chamalka98', '4B Inamaluwa Mathale', '0702941598'),
(10, 'Ishani Omanthi', 'ishani9@gmail.com', 'ishani9', '20 Peradeniya Kandy', '0768249225'),
(11, 'Dilshani Adikari', 'dilshani90@gmail.com', 'dilshani90', '4A Delthota Kandy', '0812349919'),
(12, 'Sandamini Kumari', 'sandamini97@gmail.com', 'sanda90', '77 Malsiripura Uthura', '0778828829'),
(13, 'Gihani Bandara', 'gihani88@gmail.com', 'gihani88', '4/A Digana Kandy', '0706824678'),
(14, 'Akila Aluvihara', 'akila9@gmail.com', 'akila9', '9C Homagama Colombo', '0710784366'),
(15, 'Gayani Sandareka', 'gayani@gmail.com', 'gayani88', '2A Hanwella Colombo', '0709149000'),
(16, 'Ishani Manamperi', 'ishani@gmail.com', 'ishani77', '15 Agalawaththa Kaluthara', '0727778356'),
(17, 'Ruwan Gunarathna', 'ruvan@gmail.com', 'ruwan90', '9B Ginigathhena Nawalapitiya', '0707894469');


CREATE TABLE patient(
patient_id int(11) NOT NULL,
name varchar (50) NOT NULL,
email varchar (25) NOT NULL,
password varchar (25) NOT NULL,
address varchar (50) NOT NULL,
phone varchar (15) NOT NULL,
sex varchar (10) NOT NULL,
birth_date varchar (15) NOT NULL,
age int (11) NOT NULL,
blood_group varchar (5) NOT NULL,
account_opening_timestamp int (11) NOT NULL);


INSERT INTO patient(patient_id,name,email,password,address,phone,sex,birth_date,age,blood_group,account_opening_timestamp) VALUES
(1, 'Sajana Ashini', 'sanjana@gmail.com', 'sanjana98', '13 Pilimathalawa Kandy', '0774944173', 'female', '03/04/1981', 34, 'B+', 1448984171),
(2, 'Thilini Feranando', 'thilini@gmail.com', 'thilini98', '127 Wallawaththa Colombo', '0773766893', 'female', '03/04/1981', 34, 'B+', 1448984170),
(3, 'Nalika Bandara', 'nalika@gmail.com', 'nalika90', '68 Balapitiya Galle', '0727873162', 'female', '03/31/1990', 32, 'AB+', 1620900518),
(4, 'Isuru Bandara', 'isuru@gmail.com', 'isuru76', '5 Wigula road Gampola', '0742880002', 'male', '12/14/1993', 27, 'A-', 1651060241),
(5, 'Janaki Bandara', 'janaki@gmail.com', 'janaki88', '90 Kinniya Trinkomalee', '0751996888', 'female', '04/14/1994', 28, 'AB+', 1651084360),
(6, 'Kasuni Perera', 'kasuni68@gmail.com', 'kasuni68', '5B Buttala Monaragala', '0777719894', 'female', '04/06/1999', 23, 'O+', 1651084418),
(7, 'Nimesha Konara', 'nimesha57@gmail.com', 'nimesha57', '6B Naula Mathale', '0750341290', 'female', '04/01/1990', 32, 'B+', 1651084465),
(8, 'Sudeera Bandara', 'sudeera96@gmail.com', 'sudeera96', '9A Makadura Mathara', '0773734903', 'male', '02/03/1990', 32, 'B+', 1651084514),
(9, 'Kavindi Chamalka', 'chamalka90@gmail.com', 'chamalka90', '4B Peradeniya Kandy', '0715980294', 'female', '06/03/1990', 31, 'A-', 1651084570),
(10, 'Omanthi Ishani', 'oman@gmail.com', 'oman90', '20 Inamaluwa Mathale', '0762258249', 'female', '07/08/1997', 24, 'B-', 1651084621),
(11, 'Sarani Adikari', 'sarani90@gmail.com', 'sarani90', '4A Malsiripura Uthura', '0812349919', 'female', '01/05/2000', 21, 'O+', 1651084682),
(12, 'Kumari Sandamini', 'kumari97@gmail.com', 'kumari97', '7C Delthota Kandy', '0778829882', 'female', '03/23/1998', 24, 'O-', 1651084731),
(13, 'Ganini Bandara', 'ganini88@gmail.com', 'ganini88', '4/A Homagama Colombo', '0767806824', 'female', '02/12/1980', 42, 'O+', 1651084783),
(14, 'Akash Aluvihara', 'akash9@gmail.com', 'akash9', '9C Digana Kandy', '0736610784', 'male', '04/28/1975', 46, 'A+', 1651084901),
(15, 'Sandareka Gayani', 'sada@gmail.com', 'sada88', '2A Agalawaththa Kalutharae', '0714900009', 'female', '02/17/1968', 54, 'B+', 1651084953),
(16, 'Ishi Manamperi', 'ishi@gmail.com', 'ishi77', '15 Hanwella Colombo', '0778356277', 'female', '04/10/1987', 35, 'A+', 1651085014);


CREATE TABLE payment(
payment_id int (11) NOT NULL,
payment_type varchar (100) NOT NULL,
transaction_id varchar (100) NOT NULL,
invoice_id int (11) NOT NULL,
patient_id int (11) NOT NULL,
method varchar (25) NOT NULL,
description varchar (500) NOT NULL,
amount int (11) NOT NULL,
timestamp int (11) NOT NULL);

INSERT INTO payment(payment_id,payment_type,transaction_id,invoice_id,patient_id,method,description,amount,timestamp) VALUES
(4, 'Payment 1', '544507', 3, 5, 'cash', 'threatment payment', 3000, 1651201325),
(3, 'Payment 2', '994782', 4, 6, 'cash', 'booking payment', 500, 1651170342);


CREATE TABLE prescription(
prescription_id int (11) NOT NULL,
creation_timestamp int (11) NOT NULL,
doctor_id int (11) NOT NULL,
patient_id int (11) NOT NULL,
case_history varchar (2000) NOT NULL,
medication varchar (2000) NOT NULL,
medication_from_pharmacist varchar (2000) NOT NULL,
description varchar (2000) NOT NULL);

INSERT INTO prescription(prescription_id,creation_timestamp,doctor_id,patient_id,case_history,medication,medication_from_pharmacist,description) VALUES
(6, 1651170030, 11, 6, 'history', 'medicine1', 'medicine from pharmacy', 'threatment1'),
(4, 1651161041, 8, 5, 'history2', 'medicine2', 'medicine from pharmacy2', 'threatment2');


CREATE TABLE report(
report_id int (11) NOT NULL,
type varchar (15) NOT NULL COMMENT 'operation,birth,death',
description varchar (500) NOT NULL,
timestamp int (11) NOT NULL,
doctor_id int (11) NOT NULL,
patient_id int (11) NOT NULL);


INSERT INTO report(report_id,type,description,timestamp,doctor_id,patient_id) VALUES
(3, 'birth', 'birth report', 1651161137, 8, 5),
(4, 'operation', 'operation report', 1651170081, 11, 6);


CREATE TABLE settings(
settings_id int (11) NOT NULL,
type varchar (100) NOT NULL,
description varchar (100) NOT NULL);


INSERT INTO settings(settings_id,type,description) VALUES
(1, 'system_name', 'Medicare Hospital'),
(7, 'system_email', 'medicare@mail.com'),
(2, 'system_title', 'Medicare Hospital'),
(3, 'address', '4/1, Peradeniya Road, Kandy'),
(4, 'phone', '0812312654'),
(6, 'currency', 'LKR');


ALTER TABLE `accountant`
  ADD PRIMARY KEY (`accountant_id`);


ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`);


ALTER TABLE `appointment`
  ADD PRIMARY KEY (`appointment_id`);


ALTER TABLE `bed`
  ADD PRIMARY KEY (`bed_id`);


ALTER TABLE `bed_allotment`
  ADD PRIMARY KEY (`bed_allotment_id`);


ALTER TABLE `blood_donor`
  ADD PRIMARY KEY (`blood_donor_id`);


ALTER TABLE `department`
  ADD PRIMARY KEY (`department_id`);


ALTER TABLE `diagnosis_report`
  ADD PRIMARY KEY (`diagnosis_report_id`);


ALTER TABLE `doctor`
  ADD PRIMARY KEY (`doctor_id`);


ALTER TABLE `email_template`
  ADD PRIMARY KEY (`email_template_id`);


ALTER TABLE `invoice`
  ADD PRIMARY KEY (`invoice_id`);


ALTER TABLE `laboratorist`
  ADD PRIMARY KEY (`laboratorist_id`);


ALTER TABLE `log`
  ADD PRIMARY KEY (`log_id`);


ALTER TABLE `message`
  ADD PRIMARY KEY (`message_id`);


ALTER TABLE `noticeboard`
  ADD PRIMARY KEY (`notice_id`);


ALTER TABLE `nurse`
  ADD PRIMARY KEY (`nurse_id`);


ALTER TABLE `patient`
  ADD PRIMARY KEY (`patient_id`);


ALTER TABLE `payment`
  ADD PRIMARY KEY (`payment_id`);


ALTER TABLE `prescription`
  ADD PRIMARY KEY (`prescription_id`);


ALTER TABLE `report`
  ADD PRIMARY KEY (`report_id`);


ALTER TABLE `settings`
  ADD PRIMARY KEY (`settings_id`);


ALTER TABLE `accountant`
  MODIFY `accountant_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

ALTER TABLE `admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

ALTER TABLE `appointment`
  MODIFY `appointment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

ALTER TABLE `bed`
  MODIFY `bed_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

ALTER TABLE `bed_allotment`
  MODIFY `bed_allotment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

ALTER TABLE `blood_donor`
  MODIFY `blood_donor_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

ALTER TABLE `department`
  MODIFY `department_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

ALTER TABLE `diagnosis_report`
  MODIFY `diagnosis_report_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

ALTER TABLE `doctor`
  MODIFY `doctor_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

ALTER TABLE `email_template`
  MODIFY `email_template_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `invoice`
  MODIFY `invoice_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

ALTER TABLE `laboratorist`
  MODIFY `laboratorist_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

ALTER TABLE `log`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `noticeboard`
  MODIFY `notice_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

ALTER TABLE `nurse`
  MODIFY `nurse_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

ALTER TABLE `patient`
  MODIFY `patient_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

ALTER TABLE `payment`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

ALTER TABLE `prescription`
  MODIFY `prescription_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

ALTER TABLE `report`
  MODIFY `report_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

ALTER TABLE `settings`
  MODIFY `settings_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
