# SELLING-GLASSES

1. Api register.
   - http://localhost:8088/SELLING-GLASSES/routes/web.php?action=register
   - Nhập dữ liệu dạng form: name, email, password, phone,
   - kết quả trả về là:
   * /SELLING-GLASSES/app/views/auth/auth.html
   * Please fill all fields
   - Invalid email
   - Email already exists
   - Register failed
2. Api login.

- http://localhost:8088/SELLING-GLASSES/routes/web.php?action=login
- dữ liệu nhập vào dạng form gồm: userName, password.
  -kết quả trả về lại:
- /SELLING-GLASSES/app/views/admin/dashboard.php
- /SELLING-GLASSES/app/views/staff/dashboard.php
- /SELLING-GLASSES/app/views/sales/dashboard.php
- /SELLING-GLASSES/index.php
- Role not recognized
- Invalid email or password

3. Api logout
   -http://localhost:8088/SELLING-GLASSES/routes/web.php?action=logout
   = kết quả trả về:
   +/SELLING-GLASSES/public/index.php
   +Invalid action
