# Explanation: Crawling and Displaying WordPress Plugin

## Problem Statement

The task involves creating a WordPress plugin that performs website homepage crawling, internal hyperlink extraction, and provides administrators with a way to view the results. The plugin should enable manual crawls, automatic periodic crawls, error handling, and a user-friendly interface for displaying the crawl results.

## Technical Specification

**Admin Settings Page**: A new top-level admin menu is added in the WordPress dashboard to provide administrators with access to the plugin's features. This settings page allows admins to manually trigger crawls, view the crawl results, and obtain a shortcode for embedding results on any page.

**Crawling Process**: Upon manual initiation, the crawl task is set to run immediately. Subsequent crawls are scheduled to occur every hour. The crawling process begins by clearing the previous crawl results and deleting the existing sitemap.html file. The plugin then starts crawling the website's root URL, extracts internal hyperlinks, and temporarily stores the results using the WordPress **option** API.

**Displaying Results**: The crawl results are displayed on the admin settings page, providing an overview of the internal hyperlinks. Additionally, the plugin saves the homepage's .php file as a .html file for easier viewing without PHP processing. A sitemap.html file is generated to display the crawl results in a structured sitemap format. In case of errors during crawling, the plugin provides error notices with guidance.

**Front-End Access**: On the front-end, visitors can access the sitemap.html page to view the crawl results presented in a user-friendly sitemap format, showcasing the internal hyperlinks.

## Technical Decisions and Rationale

1. **Crawling Approach**: To simplify the task, the plugin focuses on crawling only the homepage instead of recursive crawling of internal hyperlinks. This maintains simplicity while achieving the core objective of presenting internal links.

2. **Storage**: Temporary crawl results are stored in a MariaDB or MySQL database. This decision is aimed at ensuring data integrity, easy retrieval, and efficient display of results.

3. **File Handling**: The plugin creates a sitemap.html file to provide a structured representation of the crawl results.

4. **Error Handling**: Robust error handling is implemented to capture and report errors during crawling. This enhances user experience and provides administrators with guidance in case of issues.

## Code Functionality and Outcome

The plugin code executes the crawling process, extracts internal hyperlinks, and temporarily stores the results. It generates a sitemap.html file to visualize the crawl results in a structured format. Through the admin settings page, administrators can initiate manual crawls, view results, and obtain a shortcode for embedding results.

## Achieving Admin's Desired Outcome

The solution successfully meets the admin's desired outcome by providing a seamless process for manual crawling, temporary result storage, and structured display. Admins can trigger crawls, access results via the settings page, and even embed them using shortcodes. The scheduled crawls ensure up-to-date results, while error handling mechanisms maintain a smooth user interaction.

## Bonus Points Considerations

Bonus points have been considered by:
- Adhering to Coding Standards: The code conforms to coding standards, passing phpcs inspection.
- Implementing Testing: Automated unit and integration tests ensure accurate code functionality.
- Continuous Integration: The GitHub repository integrates with Travis CI for automated testing and deployment, enhancing code reliability and maintainability.

In conclusion, the WordPress plugin efficiently addresses the task by offering admins an intuitive method for crawl initiation, result display, and embedding on desired pages. The chosen technical strategies ensure data accuracy, error handling, and adherence to coding standards, culminating in a robust and user-friendly solution.
