<?php

class Person {
    private $firstName;
    private $lastName;
    private $dateOfBirth;
    private $address;

    public function getFirstName() {
        return $this->firstName;
    }

    public function setFirstName($firstName) {
        $this->firstName = $firstName;
    }

    public function getLastName() {
        return $this->lastName;
    }

    public function setLastName($lastName) {
        $this->lastName = $lastName;
    }

    public function getDateOfBirth() {
        return $this->dateOfBirth;
    }

    public function setDateOfBirth($dateOfBirth) {
        $this->dateOfBirth = $dateOfBirth;
    }

    public function getAddress() {
        return $this->address;
    }

    public function setAddress($address) {
        $this->address = $address;
    }

    public function toArray() {
        return [
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'dateOfBirth' => $this->dateOfBirth,
            'address' => $this->address,
        ];
    }

    public function fromArray($data) {
        $this->firstName = $data['firstName'];
        $this->lastName = $data['lastName'];
        $this->dateOfBirth = $data['dateOfBirth'];
        $this->address = $data['address'];
    }
}

class Employee extends Person {
    protected $jobPosition;
    protected $salary;

    public function getJobPosition() {
        return $this->jobPosition;
    }

    public function setJobPosition($jobPosition) {
        $this->jobPosition = $jobPosition;
    }

    public function getSalary() {
        return $this->salary;
    }

    public function setSalary($salary) {
        $this->salary = $salary;
    }

    public function toArray() {
        $data = parent::toArray();
        $data['jobPosition'] = $this->jobPosition;
        $data['salary'] = $this->salary;
        return $data;
    }

    public function fromArray($data) {
        parent::fromArray($data);
        $this->jobPosition = $data['jobPosition'];
        $this->salary = $data['salary'];
    }
}

class EmployeeManager {
    private $employees = [];

    public function addEmployee(Employee $employee) {
        $this->employees[] = $employee;
    }

    public function getEmployees() {
        return $this->employees;
    }

    public function updateEmployee($index, Employee $employee) {
        if (isset($this->employees[$index])) {
            $this->employees[$index] = $employee;
        }
    }

    public function deleteEmployee($index) {
        if (isset($this->employees[$index])) {
            unset($this->employees[$index]);
            $this->employees = array_values($this->employees); // Reset array indices
        }
    }

    public function saveToFile($filename) {
        $data = array_map(function($employee) {
            return $employee->toArray();
        }, $this->employees);
        file_put_contents($filename, json_encode($data, JSON_PRETTY_PRINT));
    }

    public function loadFromFile($filename) {
        if (file_exists($filename)) {
            $data = json_decode(file_get_contents($filename), true);
            foreach ($data as $item) {
                $employee = new Employee();
                $employee->fromArray($item);
                $this->addEmployee($employee);
            }
        }
    }
}

// Khởi tạo EmployeeManager
$manager = new EmployeeManager();
$manager->loadFromFile('users.json');

// Xử lý thêm, sửa và xóa nhân viên
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'add') {
            $employee = new Employee();
            $employee->setFirstName($_POST['firstName']);
            $employee->setLastName($_POST['lastName']);
            $employee->setDateOfBirth($_POST['dateOfBirth']);
            $employee->setAddress($_POST['address']);
            $employee->setJobPosition($_POST['jobPosition']);
            $employee->setSalary($_POST['salary']);
            $manager->addEmployee($employee);
        } elseif ($_POST['action'] === 'update') {
            $index = $_POST['index'];
            $employee = new Employee();
            $employee->setFirstName($_POST['firstName']);
            $employee->setLastName($_POST['lastName']);
            $employee->setDateOfBirth($_POST['dateOfBirth']);
            $employee->setAddress($_POST['address']);
            $employee->setJobPosition($_POST['jobPosition']);
            $employee->setSalary($_POST['salary']);
            $manager->updateEmployee($index, $employee);
        } elseif ($_POST['action'] === 'delete') {
            $index = $_POST['index'];
            $manager->deleteEmployee($index);
        }
        $manager->saveToFile('users.json');
    }
}

$employees = $manager->getEmployees();

?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý nhân sự</title>
    <style>
        body { font-family: 'Times New Roman', Times, serif; margin:50px; text-align:inherit; }
        h2 { color: red ; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #aaa; padding: 8px; text-align: center; }
        th { background-color: tomato; }
        td{ background-color: lightgray;}
        .button { margin-top: 10px; }
    </style>
</head>
<body>

<h2>Quản Lý Nhân Sự</h2>
<form method="post" id="employeeForm">
    <input type="hidden" name="action" value="add">
    <label>Họ:</label>
    <input type="text" name="firstName" required><br>
    <label>Tên:</label>
    <input type="text" name="lastName" required><br>
    <label>Ngày sinh:</label>
    <input type="date" name="dateOfBirth" required><br>
    <label>Địa chỉ:</label>
    <input type="text" name="address" required><br>
    <label>Vị trí:</label>
    <input type="text" name="jobPosition" required><br>
    <label>Lương:</label>
    <input type="number" name="salary" required><br>
    <input type="submit" value="Thêm nhân viên" class="button">
</form>

<h2>Danh sách nhân viên</h2>
<table>
    <thead>
        <tr>
            <th>Họ</th>
            <th>Tên</th>
            <th>Ngày sinh</th>
            <th>Địa chỉ</th>
            <th>Vị trí</th>
            <th>Lương</th>
            <th>Thao tác</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($employees as $index => $employee): ?>
            <tr>
                <td><?php echo $employee->getFirstName(); ?></td>
                <td><?php echo $employee->getLastName(); ?></td>
                <td><?php echo $employee->getDateOfBirth(); ?></td>
                <td><?php echo $employee->getAddress(); ?></td>
                <td><?php echo $employee->getJobPosition(); ?></td>
                <td><?php echo $employee->getSalary(); ?></td>
                <td>
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="index" value="<?php echo $index; ?>">
                        <input type="submit" name="action" value="delete">
                    </form>
                    <button onclick="editEmployee(<?php echo $index; ?>, '<?php echo $employee->getFirstName(); ?>', '<?php echo $employee->getLastName(); ?>', '<?php echo $employee->getDateOfBirth(); ?>', '<?php echo $employee->getAddress(); ?>', '<?php echo $employee->getJobPosition(); ?>', <?php echo $employee->getSalary(); ?>)">Sửa</button>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<script>
    function editEmployee(index, firstName, lastName, dateOfBirth, address, jobPosition, salary) {
        document.getElementById('employeeForm').action.value = 'update';
        document.getElementById('employeeForm').index.value = index;
        document.getElementsByName('firstName')[0].value = firstName;
        document.getElementsByName('lastName')[0].value = lastName;
        document.getElementsByName('dateOfBirth')[0].value = dateOfBirth;
        document.getElementsByName('address')[0].value = address;
        document.getElementsByName('jobPosition')[0].value = jobPosition;
        document.getElementsByName('salary')[0].value = salary;
    }
</script>

</body>
</html>