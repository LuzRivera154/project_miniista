# 📸 Mini Instagram

A web application that simulates the basic features of Instagram, built to practice PHP, HTML, and CSS skills.

### ✨ Features

- **Photo & Video Uploads:** Share images and videos to the feed.
- **Responsive Design:** Fully optimized for mobile, tablet, and desktop.
- **Modern Layouts:** Built using Flexbox and CSS Grid.

### 🛠️ Tech Stack

- **Frontend:** HTML5, CSS3, JavaScript
- **Backend:** PHP
- **Containerization:** Docker + Apache HTTP Server

### ⚙️ Prerequisites

- Un serveur web (Apache ou Nginx)
- PHP 7.4 ou supérieur

### 📦 Installation

**1. Clone the repository**

```bash
git clone https://github.com/LuzRivera154/project_miniista.git
cd project_miniista
```

**2. Build the Docker image**

```bash
docker build -t miniinsta .
```

**3. Start the container**

```bash
docker run -p 8080:80 --name miniinsta miniinsta
```

**4. Open your browser and go to:**

```
http://localhost:8080
```

### ⏹️ Stop the app

```bash
docker stop miniinsta
```

### 🗑️ Remove the container

```bash
docker rm miniinsta
```
