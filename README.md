# Laravel File Creator

The purpose of this package is to create app, database, and test files. This can be a replacement of Laravel's file generator commands because this package offers quite nice features.

### Installation
If you installed **[Packagified Laravel](https://github.com/bulentAkgul/packagified-laravel)**, you should have this package already. So skip installation.
```
sail composer require bakgul/laravel-file-creator
```
Next, you need to publish the settings through executing the following command. By doing so, you will have a new file named **packagify.php** in the config folder. If you check the "**files**" array, you can see the file types that can be created. A quite deep explanations are provided in the comment block of the files array.
```
sail artisan packagify:publish-config
```
After publishing stubs, you will be able to update the stub files as you need. It's safe to delete the untouched files.
```
sail artisan packagify:publish-stub
```

### Command Signature
```
create:file {name} {type} {package?} {app?} {--p|parent=} {--t|taskless} {--f|force}
```
### Expected Inputs
+ **Package**: It won't be used when you work on a Standalone Laravel or Standalone Package. If you don't specify a valid package name, the file will be generated in the App namespace.

+ **App**: Some files like controller, may have app specific. When I say app, I mean admin app, web app, desktop app etc. To create those files in the dedicated app folder, you need to specified the app name. The settings are in **apps** array on **packagify.php** file.

+ **Parent**: To create a nested controller, a parent model, or to create a listener, a parent event is required. Evaluator will warn you when a parent has to be specified.

+ **Taskless**: The file types that have tasks like service, or test, will be generated as a seperate file for each task unless tasks are specified. But sometime, you may want to create a single file without any task. To do that, you need to append "**-t**" or "**--taskless**" to your command. This will cancel the default behaviour of the task explosion.

+ **Force**: Normally, a file will not be regenerated if it exists. If this options is passed, a new file will be created anyway.

### Arguments' Schemas and Details
+ **Name**: subs/name:task

  + **subs**: You can specify subfolders like **sub1/sub2/sub3** when you need deeper file structure.

  + **name**: The file name without any suffix.

  + **task**: This is optional.
    + *exist*: You may set one or more tasks with a dot-seperatod fashion like "**users:index**" or "**users:index.store.update**." If you pass a task that isn't in the list of the given file type's task list, and the global task list (see the tasks array on **config/packagify.php**), it will be ignored.
    + *missing*: If the underlying file type has tasks, a seperate file will be generated for each of them. Otherwise a single file will be generated.

+ **Type**: name:variation

  + **name**: It's required and should be one of the keys in the *files* array on **config/packagify.php** except *view, css, js, livewire, component*. These keys will be used by **[Laravel Resource Creator](https://github.com/bulentAkgul/laravel-resource-creator)**

  + **variation**: It's optional.
    + *exist*: If the given file type has variations, you may specify which one should be created.
    + *missing*: The default variation, which is the first item in the variations array will be used.

## Packagified Laravel

The main package that includes this one can be found here: **[Packagified Laravel](https://github.com/bulentAkgul/packagified-laravel)**

## The Packages That Will Be Installed By This Package
+ **[Command Evaluator](https://github.com/bulentAkgul/command-evaluator)**
+ **[File Content](https://github.com/bulentAkgul/file-content)**
+ **[File History](https://github.com/bulentAkgul/file-history)**
+ **[Kernel](https://github.com/bulentAkgul/kernel)**