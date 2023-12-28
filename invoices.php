<?php
  $pdo = new PDO("sqlite:chinook.db");
  $sql = '
    SELECT invoices.InvoiceId, invoices.InvoiceDate, invoices.Total, customers.FirstName, customers.LastName
    FROM invoices
    INNER JOIN customers
    ON invoices.CustomerId = customers.CustomerId
  ';

  if (isset($_GET['q'])) {
    $sql = "$sql WHERE customers.FirstName LIKE :first_name";
  }

  $sql = "$sql ORDER BY invoices.InvoiceDate DESC";

  $statement = $pdo->prepare($sql);

  if (isset($_GET['q'])) {
    $boundSearchParam = '%' . $_GET['q'] . '%';
    $statement->bindParam(':first_name', $boundSearchParam);
  }

  $statement->execute();
  $invoices = $statement->fetchAll(PDO::FETCH_OBJ);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Invoices</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <div class="container">
    <h1>Invoices</h1>

    <form action="invoices.php" method="GET" class="mb-3">
      <input
        type="search"
        name="q"
        class="form-control"
        placeholder="Search by first name"
        value="<?php echo isset($_GET['q']) ? $_GET['q'] : '' ?>"
      />
    </form>

    <table class="table table-striped">
      <thead>
        <tr>
          <th>Date</th>
          <th>Invoice Number</th>
          <th>Customer</th>
          <th>Total</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($invoices as $invoice) : ?>
          <tr>
            <td>
              <?php echo $invoice->InvoiceDate ?>
            </td>
            <td>
              <?php echo $invoice->InvoiceId ?>
            </td>
            <td>
              <?php echo $invoice->FirstName . ' ' . $invoice->LastName ?>
            </td>
            <td>
              $<?php echo $invoice->Total ?>
            </td>
          </tr>
        <?php endforeach ?>
      </tbody>
    </table>
  </div>
</body>
</html>