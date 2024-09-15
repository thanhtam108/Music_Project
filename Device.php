<?php

require_once 'DBConnection.php';

class Device
{

    var $dev_id;
    var $dev_name;
    var $dev_category;
    var $dev_brand;
    var $dev_image;
    var $dev_price;

    protected $conn;

    public function __construct()
    {
        $this->dev_id = "";
        $this->dev_name = "";
        $this->dev_category = "";
        $this->dev_brand = "";
        $this->dev_image = "";
        $this->dev_price = "";
        $connectObj = new DBConnection();
        $this->conn = $connectObj->conn;
    }

    public function get_brand($brand_id)
    {
        try {
            $sql = "SELECT BRAND_NAME FROM T_BRAND WHERE BRAND_ID = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $brand_id);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                return $row['BRAND_NAME'];
            } else {
                return null; // No brand found
            }
        } catch (Exception $e) {
            die("Query failed: " . $e->getMessage());
        }
    }
    private function get_category($cate_id)
    {
        try {
            $sql = "SELECT CATE_NAME FROM T_CATEGORY WHERE CATE_ID = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $cate_id);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                return $row['CATE_NAME'];
            } else {
                return null; // No category found
            }
        } catch (Exception $e) {
            die("Query failed: " . $e->getMessage());
        }
    }

    public function get_cate_list()
    {
        try {
            $sql = "SELECT * FROM T_CATEGORY ";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();

            $result_array = array();
            $result = $stmt->get_result();

            while ($row = $result->fetch_assoc()) {
                $result_array[] = $row;
            }

            return $result_array;
        } catch (Exception $e) {
            die("Query failed: " . $e->getMessage());
        }
    }

    public function get_device_list()
    {
        try {
            $sql = "SELECT * FROM T_DEVICE ";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();

            $result_array = array();
            $result = $stmt->get_result();

            while ($row = $result->fetch_assoc()) {
                $result_array[] = $row;
            }

            return $result_array;
        } catch (Exception $e) {
            die("Query failed: " . $e->getMessage());
        }
    }

    public function get_brand_list()
    {
        try {
            $sql = "SELECT * FROM T_BRAND ";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();

            $result_array = array();
            $result = $stmt->get_result();

            while ($row = $result->fetch_assoc()) {
                $result_array[] = $row;
            }

            return $result_array;
        } catch (Exception $e) {
            die("Query failed: " . $e->getMessage());
        }
    }


    private function name_fusion($name, $cate_id)
    {
        $cate_name = $this->get_category($cate_id);
        return "$cate_name" . " " . "$name";
    }

    private function check_device_existence($dev_name, $cate_id, $brand_id)
    {
        try {
            $sql = "SELECT * FROM T_DEVICE WHERE DEV_NAME = ?
            AND CATE_ID = ?
            AND BRAND_ID = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param('sii', $dev_name, $cate_id, $brand_id);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->num_rows > 0;
        } catch (Exception $e) {
            die("Query failed: " . $e->getMessage());
        }
    }

    function get_device($id)
    {
        try {
            $sql_device = "SELECT * FROM T_DEVICE WHERE DEV_ID = ?";
            $stmt = $this->conn->prepare($sql_device);
            $stmt->bind_param('s', $id);
            $stmt->execute();

            $result = $stmt->get_result();

            if ($result->num_rows > 0) { // Kiểm tra có dòng nào trả về hay không
                $result_array = $result->fetch_assoc();

                $brand_name = $this->get_brand($result_array['BRAND_ID']);
                $cate_name = $this->get_category($result_array['CATE_ID']);

                $result_array['BRAND_NAME'] = $brand_name;
                $result_array['CATE_NAME'] = $cate_name;

                return $result_array;
            } else {
                // Không có dòng nào trả về, có thể xử lý tùy ý, ví dụ:
                return null;
            }
        } catch (Exception $e) {
            die("Query failed: " . $e->getMessage());
        }
    }

    function add_device($name, $brand_id, $cate_id, $dev_image, $dev_price)
    {
        $dev_name = $this->name_fusion($name, $cate_id);
        if ($this->check_device_existence($dev_name, $cate_id, $brand_id)) {
            echo "Device already exists in the database! Please try again!";
        } else {
            try {
                $sql = "INSERT INTO T_DEVICE (`DEV_NAME`, `CATE_ID`, `BRAND_ID`, `DEV_IMAGEURL`, `DEV_PRICE`) 
                VALUES (?, ?, ?, ?, ?)";
                $stmt = $this->conn->prepare($sql);
                $stmt->bind_param("siisi", $dev_name, $cate_id, $brand_id, $dev_image, $dev_price);
                $stmt->execute();
            } catch (Exception $e) {
                die("Query failed: " . $e->getMessage());
            }
        }
    }

    function remove_device($dev_id)
    {
        $device = $this->get_device($dev_id);
        $dev_name = $device['DEV_NAME'];
        echo ($dev_name);
        $cate_id = $device['CATE_ID'];
        $brand_id = $device['BRAND_ID'];
        if (!$this->check_device_existence($dev_name, $cate_id, $brand_id)) {
            echo "Device does not exist in the database! Please try again!";
        } else {
            try {
                $sql = "DELETE FROM T_DEVICE WHERE DEV_ID = ?";
                $stmt = $this->conn->prepare($sql);
                $stmt->bind_param("s", $dev_id);
                $stmt->execute();

                if ($stmt->affected_rows > 0) {
                    echo "Device with ID $dev_id has been successfully removed from the database.";
                } else {
                    echo "Failed to remove device with ID $dev_id from the database.";
                }
            } catch (Exception $e) {
                die("Query failed: " . $e->getMessage());
            }
        }
    }

    function update_device($dev_id, $dev_name, $cate_id, $brand_id, $dev_image, $dev_price)
    {
        if ($this->check_device_existence($dev_name, $cate_id, $brand_id)) {
            echo "Device does not exist in the database! Please try again!";
        } else {
            try {
                $sql = "UPDATE T_DEVICE SET DEV_IMAGEURL = ?, DEV_PRICE = ?
                    WHERE DEV_ID = ?";
                $stmt = $this->conn->prepare($sql);
                $stmt->bind_param("sis", $dev_image, $dev_price, $dev_id);
                $stmt->execute();

                if ($stmt->affected_rows > 0) {
                    echo "Information of device with ID $dev_id has been successfully updated.";
                } else {
                    echo "Failed to update device information with ID $dev_id.";
                }
            } catch (Exception $e) {
                die("Query failed: " . $e->getMessage());
            }
        }
    }
    function search_device($searchTerm)
    {
        $query = "SELECT d.*, b.BRAND_NAME
                  FROM T_DEVICE d
                  LEFT JOIN T_BRAND b ON d.BRAND_ID = b.BRAND_ID
                  WHERE d.DEV_NAME LIKE '%$searchTerm%' 
                  OR b.BRAND_NAME LIKE '%$searchTerm%'";

        $results = $this->conn->query($query);

        // Xử lý kết quả và trả về một mảng các thiết bị
        $devices = [];
        while ($row = $results->fetch_assoc()) {
            $devices[] = $row;
        }
        return $devices;
    }
}
