import java.io.*;
import javax.servlet.*;
import javax.servlet.http.*;
import java.sql.*;
import java.sql.DriverManager;


public class Register extends HttpServlet {
    public static Integer enterUser(String name, String email, String pass) {
	String databaseURL = "jdbc:mysql://serversetup_mysql_1:3306/Skitter?user=root&password=root&useSSL=false";
        Connection conn = null;
        try {
	        DriverManager.registerDriver(new com.mysql.jdbc.Driver ());
            conn = DriverManager.getConnection(databaseURL);
            if (conn != null) {
                System.out.println("Connected to the database");
                PreparedStatement ps1 = conn.prepareStatement("SELECT * FROM Users WHERE email=?");
                ps1.setString(1, email);
                ResultSet rs = ps1.executeQuery();
                if(!rs.isBeforeFirst()){
                    System.out.println("Connected to the database");
                    PreparedStatement ps=conn.prepareStatement
                    ("INSERT INTO Users (username, email, password) VALUES (?,?,?)");
                    ps.setString(1, name);
                    ps.setString(2, email);
                    ps.setString(3, pass);
                    int i=ps.executeUpdate();
                    if(i>0){
                        return 0;
                    }
                }
                return 1;
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
        return 2;
    }

    protected void doPost(HttpServletRequest request, HttpServletResponse response)
            throws ServletException, IOException {
        response.setContentType("text/html;charset=UTF-8");
        PrintWriter out = response.getWriter();

        String name = request.getParameter("name");
        String email = request.getParameter("email");
        String pass = request.getParameter("pass");
    	Integer result = enterUser(name, email, pass);
        if(result==0){
            RequestDispatcher rd = getServletContext().getRequestDispatcher("/index.html");
			out.println("<font color=blue>Successfully registered.</font>");
			rd.include(request, response);
        }
        if(result==1){
            System.out.println("An error occurred. Maybe user/password is invalid");
			RequestDispatcher rd = getServletContext().getRequestDispatcher("/index.html");
			out.println("<font color=red>Email already in use.</font>");
			rd.include(request, response);
        }
    }
}
