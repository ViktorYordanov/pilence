ideas:
- make the maskot interractable: make a hat on the bottom-left that has blinking eyes from time-to-time and when it is clicked then the phoenix "POOFs" and appears. Make it be able to be moved around and de-summonned
- make the top part of the homepage animated on scroll with a short animation - probably using svg animations like 1 of the saved examples
- create emojis so that the visitors can react to projects/posts
- add an interactive way to have a hyperlink to the live project in the project's page - maybe have the mascot say something and guide you to it, or have an interactive hover/popyp/button that is not tied to the project's description that will lead you to the live project/website


multipager:
- homepage
- projects - with sub categories
- services
- about me
- admin panel where everything can be controlled

footer with contacts + "ask me anything" form popup

breakdown of the pages:
- homepage:
    - a little from everything (in progress..)

- projects:
    - each category has it's own page with cards of the projects that are added to that category - /projects/{category_slug}
    - each project will have it's own page too - /projects/{categopry_slug}/{project_slug}
    - each project will be assigned to only 1 category

- services:
    - packages/services divided and displayed
    - 

- about me:
    - a biography page with background/origin story, interests, hobbies, etc.

- admin panel
    - page to cistomize every aspect of the website, all the visible content


Notes:
- every project is managed separately from eachother. Even if a project has the same images as another, they are gonna be treated as different images and related only to 1 project
- the images of the projects must be able to be ordered
- one of the images can be used as cover
- at first, each project needs to have a cover image, title
- card with rounded edges (rectangular), with image full-size, on-hover effect (burn effect from the center outwards that reveals the name)