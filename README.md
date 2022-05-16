# Laravel File Creator

This package aims to create the app, database, and test files. It can be a replacement for Laravel's file generator because this package offers some quite nice features. It offers more file types like interface, trait, service, etc. Depending on the settings, it can create dozens of files with a single command and connect them to each other properly.  

### **Installation**

If you installed [**Packagified Laravel**](https://github.com/bulentAkgul/packagified-laravel), you should have this package already. So skip installation.
```
composer require bakgul/laravel-file-creator
```
Next, you need to publish the settings by executing the following command. By doing so, you will have a new file named *config/packagify.php* in the config folder. If you check the "**files**" array, you can see the file types that can be created. Quite deep explanations are provided in the comment block of the files array.
```
sail artisan packagify:publish-config
```
After publishing stubs, you will be able to update the stub files as you need. It's safe to delete the unedited files.
```
sail artisan packagify:publish-stub
```

### **Command Signature**
```
create:file {name} {type} {package?} {app?} {--p|parent=} {--t|taskless} {--f|force}
```

### **Arguments and Options**

-   **name**: subs/name:task
    -   **subs**: You can specify subfolders like **sub1/sub2/sub3** when you need a deeper file structure than the file types path_schema provides.

    -   **name**: The file name without any suffix.
 
    -   **task**: This is optional.
        -   *exist*: You may set one or more tasks with a dot-separated fashion like "**users:index**" or "**users:index.store.update**." The task should be in the file type and its pairs' and the global task lists (see the tasks array on *config/packagify.php*). Otherwise, it will be ignored.
        -   *missing*: If the underlying file type has tasks, a separate file will be generated for each of them. Otherwise, a single file will be generated.

-   **Type**: name:variation
    -   **name**: It's required and should be one of the keys in the **files** array on *config/packagify.php* except for **the view, css, js, livewire, and component**. These keys will be used by [**Laravel Resource Creator**](https://github.com/bulentAkgul/laravel-resource-creator). All detailed explanations can be found in the comment block of the **files** array.
    
    -   **variation**: It's optional.
        -   *exist*: If the given file type has variations, you may specify which one should be created.
        -   *missing*: The default variation, which is the first item in the variations array, will be used.

-   **Package**: It won't be used when working on a Standalone Laravel or Standalone Package. If you don't specify a valid package name, the file will be generated in the App namespace.

-   **App**: Some files, like the controller, may have app-specific. When I say app, I mean admin, web, desktop, etc. To create those files in the dedicated app folder, you must specify the app name. The settings are in the **apps** array on the *config/packagify.php* file.

-   **Parent**: To create a nested controller, a parent model, or to create a listener, a parent event is required. Evaluator will warn you when a parent has to be specified.

-   **Taskless**: The file types that have tasks like service, or test, will be generated as a separate file for each task unless tasks are specified. But sometimes, you may want to create a single file without any task. In such cases, You need to append "**-t**" or "**--taskless**" to your command. This will cancel the default behaviour of the task explosion.

-   **Force**: Normally, a file will not be regenerated if it exists. If this option is passed, a new file will be created anyway.

## Packagified Laravel

The main package that includes this one can be found here: [**Packagified Laravel**](https://github.com/bulentAkgul/packagified-laravel)

## The Packages That Will Be Installed By This Package

-   [**Command Evaluator**](https://github.com/bulentAkgul/command-evaluator)
-   [**File Content**](https://github.com/bulentAkgul/file-content)
-   [**File History**](https://github.com/bulentAkgul/file-history)
-   [**Kernel**](https://github.com/bulentAkgul/kernel)