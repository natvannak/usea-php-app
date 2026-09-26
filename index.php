<?php

$host = getenv('MYSQL_HOST');
$dbname = getenv('MYSQL_DB');
$username = getenv('MYSQL_USER');
$password = getenv('MYSQL_PASSWORD');

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password
    );

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "<h1>Docker Image V2</h1>";
    
    echo "<h2>Database Connected Successfully!</h2>";

    $sql = "
        CREATE TABLE IF NOT EXISTS students (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            gender VARCHAR(10) NOT NULL,
            age INT NOT NULL,
            email VARCHAR(100),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ";

    $pdo->exec($sql);

    echo "<p>Students table is ready.</p>";

    $count = $pdo->query("SELECT COUNT(*) FROM students")->fetchColumn();

    if ($count == 0) {

        $stmt = $pdo->prepare("
            INSERT INTO students (name, gender, age, email)
            VALUES (?, ?, ?, ?)
        ");

        $stmt->execute([
            'Vannak',
            'Male',
            20,
            'vannak@example.com'
        ]);

        $stmt->execute([
            'Sokha',
            'Female',
            21,
            'sokha@example.com'
        ]);

        echo "<p>2 students inserted successfully.</p>";
    }

    $stmt = $pdo->query("
        SELECT id, name, gender, age, email, created_at
        FROM students
        ORDER BY id
    ");

    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "<h2>Student List</h2>";

    echo "<table border='1' cellpadding='10' cellspacing='0'>";

    echo "
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Gender</th>
            <th>Age</th>
            <th>Email</th>
            <th>Created At</th>
        </tr>
    ";

    foreach ($students as $student) {

        echo "<tr>";

        echo "<td>" . htmlspecialchars($student['id']) . "</td>";
        echo "<td>" . htmlspecialchars($student['name']) . "</td>";
        echo "<td>" . htmlspecialchars($student['gender']) . "</td>";
        echo "<td>" . htmlspecialchars($student['age']) . "</td>";
        echo "<td>" . htmlspecialchars($student['email']) . "</td>";
        echo "<td>" . htmlspecialchars($student['created_at']) . "</td>";

        echo "</tr>";
    }

    echo "</table>";

} catch (PDOException $e) {

    echo "<h2>Database Connection Failed</h2>";
    echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
}

?>