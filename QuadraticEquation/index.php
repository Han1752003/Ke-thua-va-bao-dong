<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giải Phương Trình Bậc Hai</title>
</head>

<body>
    <h2>Giải Phương Trình Bậc Hai: ax² + bx + c = 0</h2>

    <form method="POST">
        <label>Hệ số a:</label>
        <input type="number" name="a" required>
        <br>

        <label>Hệ số b:</label>
        <input type="number" name="b" required>
        <br>

        <label>Hệ số c:</label>
        <input type="number" name="c" required>
        <br>

        <input type="submit" value="Giải">
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $a = $_POST['a'];
        $b = $_POST['b'];
        $c = $_POST['c'];

        class QuadraticEquation {
            private $a, $b, $c;

            public function __construct($a, $b, $c) {
                $this->a = $a;
                $this->b = $b;
                $this->c = $c;
            }

            public function getDiscriminant() {
                return ($this->b ** 2) - (4 * $this->a * $this->c);
            }

            public function getRoots() {
                $delta = $this->getDiscriminant();
                if ($delta > 0) {
                    return [
                        (-$this->b + sqrt($delta)) / (2 * $this->a),
                        (-$this->b - sqrt($delta)) / (2 * $this->a)
                    ];
                } elseif ($delta == 0) {
                    return [(-$this->b) / (2 * $this->a)];
                } else {
                    return null;
                }
            }
        }

        $equation = new QuadraticEquation($a, $b, $c);
        $roots = $equation->getRoots();

        echo "<h3>Kết quả:</h3>";
        if ($roots === null) {
            echo "Phương trình không có nghiệm thực.";
        } elseif (count($roots) == 2) {
            echo "Phương trình có hai nghiệm: x1 = {$roots[0]}, x2 = {$roots[1]}";
        } else {
            echo "Phương trình có nghiệm kép: x = {$roots[0]}";
        }
    }
    ?>
</body>

</html>