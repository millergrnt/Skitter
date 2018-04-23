import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.SQLException;
import java.sql.*;

public class DBConnect1 {
    public static void main(String[] args) {
        String databaseURL = "jdbc:mysql://serversetup_mysql_1:3306/Skitter?user=root&password=root&useSSL=false";
        Connection conn = null;
        try {
            conn = DriverManager.getConnection(databaseURL);
            if (conn != null) {
                System.out.println("Connected to the database");
		Statement stmt = conn.createStatement();
         	String sql;
         	sql = "SELECT * FROM Users";
         	ResultSet rs = stmt.executeQuery(sql);

         // Extract data from result set
         	while(rs.next()){
            //Retrieve by column name
            		String first = rs.getString("name");

            //Display values
            		System.out.println(", First: " + first);
         	}
            }


 	    PreparedStatement ps=conn.prepareStatement
                  ("insert into Users values(?,?,?)");

            ps.setString(1, "N");
            ps.setString(2, "C");
            ps.setString(3, "Q");
            int i=ps.executeUpdate();

          if(i>0)
          {
            System.out.println("You are sucessfully registered");
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
