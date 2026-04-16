<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?php echo $__env->yieldContent('title', 'Dashboard - Bonn DIG'); ?></title>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?php echo e(asset('favicon.ico')); ?>">
    <link rel="shortcut icon" href="<?php echo e(asset('favicon.ico')); ?>">



    <script src="https://cdn.tailwindcss.com"></script>

    <script>

        tailwind.config = {

            theme: {

                extend: {

                    fontFamily: {

                        sans: ['Instrument Sans', 'ui-sans-serif', 'system-ui', 'sans-serif'],

                    }

                }

            }

        }

    </script>

</head>

<body class="bg-gray-50 min-h-screen">

    <?php echo $__env->yieldContent('content'); ?>

</body>

</html>

<?php /**PATH C:\xampp\htdocs\bonn-dig-final-bos\resources\views/layouts/app.blade.php ENDPATH**/ ?>