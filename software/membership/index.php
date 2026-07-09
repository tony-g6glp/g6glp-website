<?php
require_once __DIR__ . '/../../include/bootstrap.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Club Membership System - G6GLP</title>
<link rel="stylesheet" href="/g6glp/include/css.css">

</head>

<body>

<header>

<h1>Club Membership System</h1>
<h2>Administration • Renewals • Database • Reporting</h2>

<p>
A web-based membership management system designed to handle
club records, renewals, communications, and administration tasks
for amateur radio and community organisations.
</p>

</header>
<?php include __DIR__ . '/../../include/public-nav.php'; ?>

<div class="container">

<section id="overview">

<h2 style="margin-bottom:30px;">System Overview</h2>

<div class="cards">

<div class="card">
<h3>Purpose</h3>
<p>
Centralised membership tracking for clubs including call signs,
contact details, subscription status, and history.
</p>
</div>

<div class="card">
<h3>Built With</h3>
<p>
PHP, MySQL, HTML/CSS, JavaScript and Apache on Linux.
Designed for simplicity, speed and reliability.
</p>
</div>

<div class="card">
<h3>Key Features</h3>
<p>
Renewal tracking, email lists, printable reports,
and secure login-based administration.
</p>
</div>

</div>

</section>

<section id="modules">

<h2 style="margin:40px 0 30px;">Modules</h2>

<div class="cards">

<div class="card">
<h3>Member Database</h3>
<p>
Store and manage member records including callsign, address,
membership type and status.
</p>
Coming Soon
</div>

<div class="card">
<h3>Renewals</h3>
<p>
Track subscription dates, generate reminders,
and manage overdue memberships.
</p>
Coming Soon
</div>

<div class="card">
<h3>Email System</h3>
<p>
Send bulk emails to members, newsletters,
and renewal reminders.
</p>
Coming Soon
</div>

<div class="card">
<h3>Reports</h3>
<p>
Generate printable reports for committees,
AGMs and membership analysis.
</p>
<a href="reports/">Open</a>
</div>

<div class="card">
<h3>Administration</h3>
<p>
User access control, system settings,
and audit logging for changes.
</p>
<a href="admin/">Open</a>
</div>

<div class="card">
<h3>Data Export</h3>
<p>
Export membership data to CSV, Excel,
or backup formats.
</p>
<a href="export/">Open</a>
</div>

</div>

</section>

<section id="admin">

<h2 style="margin:40px 0 30px;">Administration Notes</h2>

<div class="cards">

<div class="card">
<h3>Security</h3>
<p>
All administrative functions require authenticated login
and role-based access control.
</p>
</div>

<div class="card">
<h3>Backups</h3>
<p>
Regular database backups are supported and can be automated
via cron jobs.
</p>
</div>

<div class="card">
<h3>Deployment</h3>
<p>
Designed for Apache/PHP hosting environments,
including Raspberry Pi servers.
</p>
</div>

</div>

</section>

</div>

<footer>

<p><strong>Tony Rider - G6GLP</strong></p>
<p>Club Membership System � Amateur Radio Administration Tools</p>

<p>
<a href="/g6glp/software/">← Back to Software</a>
</p>

</footer>

</body>
</html>
