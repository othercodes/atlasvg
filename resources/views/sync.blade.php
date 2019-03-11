<!DOCTYPE html>
<html>
  <head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css"
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
  </head>
  <body>
    <main role="main" class="container">
    <h1>Sync</h1>
    <table class="table">
      <thead>
        <tr>
          <th scope="col">Name</th>
          <th scope="col">Surname</th>
          <th scope="col">Title</th>
          <th scope="col">Department</th>
          <th scope="col">Email</th>
        </tr>
      </thead>
      <tbody>
        @isset($events)
          @foreach($events as $event)
            <tr>
              <td>{{ $event->getGivenName() }}</td>
              <td>{{ $event->getSurname() }}</td>
              <td>{{ $event->getJobTitle() }}</td>
              <td>{{ $event->getDepartment() }}</td>
              <td>{{ $event->getUserPrincipalName() }}</td>
            </tr>
          @endforeach
        @endif
      </tbody>
    </table>
  </main>
  </body>
</html>