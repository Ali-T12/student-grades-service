# Student Grades Service

A simple PHP + MySQL web application for managing and displaying student grades.  
This project is containerized using Docker and deployed to a cloud platform as part of the Operating Systems Lab assignments.

---

## 🛠️ Technologies Used
- PHP 8.2
- MySQL 8
- Docker & Docker Compose
- Railway (Cloud Deployment)
- GitHub

---

## 📁 Project Structure
student-grades-service/
├── app/
│ └── db.php
├── public/
│ ├── index.php
│ └── style.css
├── docs/
├── docker-compose.yml
├── Dockerfile
├── init.sql
├── README.md


---

## 🚀 Running the Project Locally (Docker)

### Prerequisites
- Docker
- Docker Compose

### Steps
```bash
docker compose up --build

Then open your browser at:

http://localhost:8080


### Steps
```bash
docker compose up --build
Then open your browser at:

arduino
Copy code
http://localhost:8080
☁️ Deployment on Railway (VPS-style Cloud)
This project is deployed on Railway, which provides a browser-based terminal and container hosting.

Deployment Steps
1-Create a new Railway project.

2-Deploy the project from the GitHub repository.

3-Add a MySQL database service.

4-Configure environment variables for the web service.

5-Railway automatically builds and runs the Docker container.

🔐 Environment Variables
The application uses the following environment variables (configured in Railway):

Variable Name	Description
MYSQLHOST	    Database host
MYSQLDATABASE	Database name
MYSQLUSER	    Database username
MYSQLPASSWORD	Database password
MYSQLPORT	    Database port (default: 3306)

These variables are read in app/db.php using getenv().

🗄️ Database Initialization
The database schema is defined in:


Copy code
init.sql
Tables are created automatically when the MySQL container starts.

🌐 Production URL

Copy code
https://student-grades-service-production.up.railway.app
🧪 Verification
Docker image builds successfully.

Container runs without errors.

Application connects to the database.

Web interface is accessible via browser.

All changes are tracked in GitHub commit history.

📝 Notes (Challenges Faced)
Faced Apache MPM conflicts during deployment.

Solved MySQL connection issues by aligning environment variable names.

Learned how cloud platforms differ from local Docker networking.

Gained hands-on experience debugging real deployment issues.

👤 Author
Ali Jamal Isayed
Student ID: 120220484