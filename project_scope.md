For the test assignment, we would like you to create a simple resource management application using Laravel and Vue.js.

The application should have two pages:

1. /admin - resource management page
2. / - "home" page where all added resources would be listed

No authentication or any kind of user roles or permissions are needed.

The "admin" page of the application should be created with Vue.js component included in a blade template.

On the "admin" page you should be able to add, delete or edit resources.

Resources on the "home" page can be listed simply with a blade template or Vue.js component.

There should be three types of resources:

1. PDF download

   Admin part: Input for title and file upload dialog.
   Visitor part: Clickable link to download a file.

2. HTML snippet

   Admin part: Input for the title, Textarea for snippet description, and place to enter HTML snippet.
   Visitor part: Display description, HTML snippet, and button to copy HTML snippet to clipboard.

3. Link

   Admin part: Input for the title, input for the link, checkbox "Open in a new tab".
   Visitor part: Clickable link which should open a page in the same or new tab.
