import java.io.IOException;
import java.io.PrintWriter;
import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.SQLException;
import java.sql.*;
import javax.servlet.RequestDispatcher;
import javax.servlet.ServletException;
import javax.servlet.annotation.WebServlet;
import javax.servlet.http.Cookie;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import java.text.DateFormat;  
import java.text.SimpleDateFormat;  
import java.util.Date;  
import java.util.Calendar;  

/**
 * Servlet implementation class LoginServlet
 */
public class isAuthenticated extends HttpServlet {
	String databaseURL = "jdbc:mysql://localhost:3306/test?user=root&password=password&useSSL=false";
    Connection conn = null;

	protected void doPost(HttpServletRequest request,
			HttpServletResponse response) throws ServletException, IOException {

		// get request parameters for userID and password
		String session = request.getParameter("sessID");
		try {
                DriverManager.registerDriver(new com.mysql.jdbc.Driver ());
                conn = DriverManager.getConnection(databaseURL);
                if (conn != null) {
          		    PreparedStatement ps1 = conn.prepareStatement("SELECT * FROM Users WHERE sessID=?");
                    ps1.setString(1, session);
                    ResultSet rs = ps1.executeQuery();
					if(rs.next()){
                        PrintWriter out= response.getWriter();
						out.println("<font color=red>OK</font>");
					}else{
						RequestDispatcher rd = getServletContext().getRequestDispatcher("/login.html");
						PrintWriter out= response.getWriter();
						out.println("<font color=red>Fail</font>");
						rd.include(request, response);
					}
				}
		}  catch (SQLException ex) {
            System.out.println("An error occurred. Maybe user/password is invalid");
            ex.printStackTrace();
			RequestDispatcher rd = getServletContext().getRequestDispatcher("/login.html");
			PrintWriter out= response.getWriter();
			out.println("<font color=red>Either user name or password is wrong.</font>");
			rd.include(request, response);
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
