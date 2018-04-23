import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.SQLException;

public class AccessDatabase {
    public static void main(String[] args) {
        String databaseURL = "jdbc:mysql://serversetup_mysql_1:3306/Skitter?user=root&password=root";
        Connection conn = null;
        try {
            conn = DriverManager.getConnection(databaseURL);
            if (conn != null) {
                System.out.println("Connected to the database");
            }
        } catch (SQLException ex) {
            System.out.println("An error occurred. Maybe user/password is invalid");
            ex.printStackTrace();
        } finally {
            if (conn != null) {
                try {
                    conn.close();
                } catch (SQLException ex) {
                    ex.printStackTrace();
                }
            }
        }
    }
}
