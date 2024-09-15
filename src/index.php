<?php

define('DATABASE_HOST', 'mysql');
define('DATABASE_USER', 'coffee');
define('DATABASE_PASSWORD', 'secret');
define('DATABASE_NAME', 'coffeedb');

// Connect to the database
$db = mysqli_connect(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD, DATABASE_NAME);
define('DB', $db);

if (mysqli_connect_errno()) {
    die("Connection failed: " . mysqli_connect_error());
}

/** @var array $coffeShops */
$coffeShops = mysqli_fetch_all(mysqli_query($db, "SELECT * FROM shops"), MYSQLI_ASSOC);

function renderAdminIndex()
{
    ?>
      <section class="py-12">
        <header class="container mx-auto px-4">
          <h2 class="text-5xl tracking-tight font-black mb-4">Admin Panel</h2>
          <p class="mb-4 text-black/75 font-semibold">
            Welcome to the admin panel. Here you can add, edit and delete coffee shops.
          </p>
          <a href="/admin/new-shop" class="bg-blue-500 font-bold text-white py-2 px-4 rounded-lg">
            Add a new Coffee Shop
          </a>
        </header>
        <main class="container mx-auto px-4">
          <table class="w-full bg-white shadow-md rounded mb-6">
            <thead class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
              <tr>
                <th class="px-6 py-3 text-left">Name</th>
                <th class="px-6 py-3 text-left">Address</th>
                <th class="px-6 py-3 text-left">Rating</th>
                <th class="px-6 py-3 text-left">Actions</th>
              </tr>
            </thead>
            <tbody class="text-gray-600 text-sm font-light">
          <?php
            $result = mysqli_query(DB, "SELECT * FROM shops");
    while ($coffeeShop = mysqli_fetch_assoc($result)):
        ?>
                <tr class="border-b border-gray-200 hover:bg-gray-100">
                  <td class="px-6 py-3 text-left whitespace-nowrap">
                    <?php echo htmlspecialchars($coffeeShop["name"]); ?>
                  </td>
                  <td class="px-6 py-3 text-left">
                    <?php echo htmlspecialchars($coffeeShop["location"]); ?>
                  </td>
                  <td class="px-6 py-3 text-center">
                    <?php echo htmlspecialchars($coffeeShop["rating"]); ?>
                  </td>
                  <td class="px-6 py-3 text-left">
                    <a
                      href="/admin/edit-shop/<?php echo $coffeeShop["id"]; ?>"
                    class="text-blue-600 hover:text-blue-700 transition-colors duration-150 ease-in"
                    >
                      Edit
                    </a>
                    <form
                      method="post"
                      action="/admin/delete-shop/<?php echo $coffeeShop["id"]; ?>"
                    >
                      <button
                        type="submit"
                        class="ml-2 text-red-600 hover:text-red-700 transition-colors duration-150 ease-in"
                        name="delete_coffe_shop"
                      >
                        Delete
                      </button>
                    </form>
                  </td>
                </tr>
              <?php endwhile; ?>
            </tbody>
        </main>
      </section>
<?php
}

function renderAdminNewShop()
{
    die("Not implemented yet");
}

function renderAdminEditShop()
{
    die("Not implemented yet");
}

function renderAdminDeleteShop()
{
    die("Not implemented yet");
}

function renderAdmin()
{
    switch ($_SERVER["REQUEST_URI"]) {
        case "/admin":
            renderAdminIndex();
            break;
        case "/admin/new-shop":
            renderAdminNewShop();
            break;
        case "/admin/edit-shop":
            renderAdminEditShop();
            break;
        case "/admin/delete-shop":
            renderAdminDeleteShop();
            break;
        default:
            die("Page not found");
            break;
    }
}

function render(string $page)
{
    switch ($page) {
        case "admin":
            renderAdmin();
            break;
        default:
            die("Page not found");
            break;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coffee Shops Near You</title>
    <link rel="stylesheet" href="/styles.css">
  </head>
  <body class="antialiased bg-gray-100">
    <?php render("admin"); ?>
    <main class="py-12">
      <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
          <?php foreach ($coffeShops as $coffeeShop): ?>
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
              <img
                src="/images/<?php echo $coffeeShop["image"]; ?>"
                alt="<?php echo $coffeeShop["name"]; ?>"
                class="w-full"
                loading="lazy"
              />
              <div class="p-6">
                <h2 class="text-xl font-bold mb-2"><?php echo $coffeeShop["name"]; ?></h2>
                <p class="text-gray-700 text-base"><?php echo $coffeeShop["address"]; ?></p>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </main>
  </body>
</html>
