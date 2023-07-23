Jawaban Soal Implementation Test - Backend (2)

1. Tampilkan seluruh data dari tabel "employees"
Jawaban: SELECT * FROM employees;
Output: https://prnt.sc/prg2D4WZF3JB

2. Berapa banyak karyawan yang memiliki posisi pekerjaan (job title) "Manager"?
Jawaban: SELECT * FROM `employees` WHERE `job_title` = 'Manager';
Output: https://prnt.sc/LzDLS44OxNcE

3. Tampilkan daftar nama dan gaji (salary) dari karyawan yang bekerja di departemen "Sales" atau
"Marketing"
Jawaban: SELECT `name`, `salary`, `employee_id` FROM `employees` WHERE `departement` IN ('Sales','Marketing');
Output: https://prnt.sc/qpzULMP9N0QA

4. Hitung rata-rata gaji (salary) dari karyawan yang bergabung (joined) dalam 5 tahun terakhir (berdasarkan
kolom "joined_date")
Jawaban: SELECT AVG(`salary`) FROM `employees` WHERE `joined_date` >= '2018-01-01';
Output: https://prnt.sc/pT2S_Ierq2WC

5. Tampilkan 5 karyawan dengan total penjualan (sales) tertinggi dari tabel "employees" dan "sales_data"
Jawaban: SELECT name,employees.employee_id, SUM(sales) as total FROM employees INNER JOIN sales_data ON sales_data.employee_id = employees.employee_id GROUP BY name, employees.employee_id ORDER BY total DESC LIMIT 5
Output: https://prnt.sc/jfBsjtQzNuUf

6. Tampilkan nama, gaji (salary), dan rata-rata gaji (salary) dari semua karyawan yang bekerja di departemen yang memiliki rata-rata gaji lebih tinggi dari gaji rata-rata di semua departemen
Jawaban: SELECT employ.name AS nama, employ.salary AS gaji, employ.departement, (SELECT AVG(salary) FROM employees WHERE departement = employ.departement) AS rata_rata_gaji FROM employees employ WHERE (SELECT AVG(salary) FROM employees WHERE departement = employ.departement) > (SELECT AVG(salary)
    FROM employees
);
Output: https://prnt.sc/JgOnK65PGsql

7. Tampilkan nama dan total penjualan (sales) dari setiap karyawan, bersama dengan peringkat (ranking) masing-masing karyawan berdasarkan total penjualan. Peringkat 1 adalah karyawan dengan total penjualan tertinggi
Jawaban: SELECT e.name, SUM(s.sales) AS total_sales, DENSE_RANK() OVER (ORDER BY SUM(s.sales) DESC) AS peringkat FROM employees e JOIN sales_data s ON e.employee_id = s.employee_id GROUP BY e.name ORDER BY peringkat;
Output: https://prnt.sc/i4fq39iPflRg

8. Buat sebuah stored procedure yang menerima nama departemen sebagai input, dan mengembalikan daftar karyawan dalam departemen tersebut bersama dengan total gaji (salary) yang mereka terima
Jawaban: DELIMITER //
CREATE PROCEDURE GetSalariesDepartmentEmployees(IN departmentName VARCHAR(255))
BEGIN
    SELECT *
    FROM employees e
    WHERE e.departement = departmentName;
END //
DELIMITER ;

Query: CALL GetSalariesDepartmentEmployees('Marketing')
Output: https://prnt.sc/rM3mm6B0QkyJ