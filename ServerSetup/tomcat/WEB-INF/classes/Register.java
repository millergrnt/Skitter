import java.io.*;
import javax.servlet.*;
import javax.servlet.http.*;
import java.sql.*;
import java.sql.DriverManager;


public class Register extends HttpServlet {
    public static void enterUser(String name, String email, String pass) {
	String databaseURL = "jdbc:mysql://localhost:3306/test?user=root&password=password&useSSL=false";
        Connection conn = null;
        try {
	    DriverManager.registerDriver(new com.mysql.jdbc.Driver ());
            conn = DriverManager.getConnection(databaseURL);
            if (conn != null) {
                System.out.println("Connected to the database");
                Statement stmt = conn.createStatement();
                String sql;
                sql = "SELECT * FROM Users WHERE email=" + user + " pass=" + pwd;
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

            ps.setString(1, name);
            ps.setString(2, email);
            ps.setString(3, pass);
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
 
    protected void doPost(HttpServletRequest request, HttpServletResponse response)
            throws ServletException, IOException {
        response.setContentType("text/html;charset=UTF-8");
        PrintWriter out = response.getWriter();
	
        String name = request.getParameter("name");
        String email = request.getParameter("email");
        String pass = request.getParameter("pass");
    	enterUser(name, email, pass);  
    }
  }
