<html>
<body>
<?php 
class Calc {

    protected $_stackOperator = array('@');
    protected $_stackOut = array();
    protected $_operator = array('@', '(', ')', '+', '-', '*', '/');
    protected $_priority = array('@' => 0, '(' => 10, ')' => 10, '+' => 20, '-' => 20, '*' => 30, '/' => 30);

    public function __construct($expression) {
        $this->convert($expression);
    }

    /**
     * 解析字符串表达式
     * 解析字符串表达式，将数字和运算符分离，用数组存储
     * @param string $expression
     * @return array
     */
    protected function expressionParase($expression) {
        $arr = str_split($expression);
        $data = $tmp = array();
        do {
            $item = array_shift($arr);
            if (in_array($item, $this->_operator)) {
                if ($tmp) {
                    array_push($data, implode('', $tmp));
                    $tmp = array();
                }
                array_push($data, $item);
            } else {
                array_push($tmp, $item);
            }

        } while(count($arr));
        array_push($data, implode('', $tmp));
        return $data;
    }

    /**
     * 生成逆波兰式
     * @param string $expression
     */
    protected function convert($expression) {
        foreach ($this->expressionParase($expression) as $char) {
            if (preg_match("/^[0-9]+$/", $char)) {
                array_push($this->_stackOut, $char);
            } else if (in_array($char, $this->_operator)) {
                if ('(' == $char) {
                    array_push($this->_stackOperator, $char);
                } else if (')' == $char) {
                    while (count($this->_stackOperator) > 1) {
                        $drop = array_pop($this->_stackOperator);
                        if ('(' == $drop) {
                            break;
                        } else {
                            array_push($this->_stackOut, $drop);
                        }
                    }
                } else {
                    while (count($this->_stackOperator)) {
                        $oTop = end($this->_stackOperator);
                        if ($this->_priority[$char] > $this->_priority[$oTop]) {
                            array_push($this->_stackOperator, $char);
                            break;
                        } else {
                           $drop = array_pop($this->_stackOperator);
                            array_push($this->_stackOut, $drop);
                        }
                    }
                }
            }
        }

        while (count($this->_stackOperator)) {
            $drop = array_pop($this->_stackOperator);
            if ('@' == $drop) {
                break;
            } else {
                array_push($this->_stackOut, $drop);
            }
        }
    }

    /**
     * 获取逆波兰式
     * @return string
     */
    public function getExpression() {
        return implode('', $this->_stackOut);
    }

    /**
     * 计算逆波兰式
     * @return int
     */
    public function calculate() {
        $stack = array();
        foreach ($this->_stackOut as $char) {
            if (preg_match("/^[0-9]+$/", $char)) {
                array_push($stack, $char);
            } else if (in_array($char, $this->_operator)) {
                $b = array_pop($stack);
                $a = array_pop($stack);

                array_push($stack, $this->operator($a, $b, $char));
            }
        }

        return end($stack);
    }

    protected function operator($a, $b, $o) {
        switch ($o) {
            case '+':
                return intval($a) + intval($b);
                break;
            case '+':
                return intval($a) + intval($b);
                break;
            case '-':
                return intval($a) - intval($b);
                break;
            case '*':
                return intval($a) * intval($b);
                break;
            case '/':
                return intval($a) / intval($b);
                break;
        }
    }
}


?>
</body>
</html>