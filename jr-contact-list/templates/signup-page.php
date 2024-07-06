<?php
// Template Name: Sign Up Page

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    // Handle form submission here (validate, sanitize, save to DB)
    $name = sanitize_text_field($_POST['name']);
    $address = sanitize_text_field($_POST['address']);
    $phone = sanitize_text_field($_POST['phone']);
    $email = sanitize_email($_POST['email']);
    $hobbies = sanitize_text_field($_POST['hobbies']);

    // Insert into DB
    global $wpdb;
    $table_name = $wpdb->prefix . 'contact_list';

    $wpdb->insert($table_name, array(
        'name' => $name,
        'address' => $address,
        'phone' => $phone,
        'email' => $email,
        'hobbies' => $hobbies
    ));

    // Redirect after form submission
    wp_redirect(home_url('/sign-up-success'));
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <link rel="stylesheet" href="<?php echo esc_url(plugin_dir_url(__FILE__) . '../css/contact-list.css'); ?>">
</head>
<body>
    <h2>Sign Up Form</h2>
    <form method="post" action="">
        <label for="name">Name:</label><br>
        <input type="text" id="name" name="name" required><br><br>

        <label for="address">Address:</label><br>
        <input type="text" id="address" name="address" required><br><br>

        <label for="phone">Phone:</label><br>
        <input type="tel" id="phone" name="phone" required><br><br>

        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" required><br><br>

        <label for="hobbies">Hobbies (max 3):</label><br>
        <div class="taglist-container" id="taglist">
            <input type="text" id="taglist-input" autocomplete="off">
            <div class="taglist-suggestions" id="taglist-suggestions"></div>
        </div>
        <input type="hidden" id="hobbies" name="hobbies" required><br><br>

        <button type="submit" name="submit">Submit</button>
    </form>

    <script>
        const predefinedHobbies = ['fishing','cricket', 'football', 'video game', 'drawing', 'gardening', 'learning', 'running', 'coding', 'photography', 'singing', 'gardening', 'travelling'];
        const maxHobbies = 3;
        let selectedHobbies = [];

        const taglistContainer = document.getElementById('taglist');
        const taglistInput = document.getElementById('taglist-input');
        const taglistSuggestions = document.getElementById('taglist-suggestions');
        const hiddenInput = document.getElementById('hobbies');

        taglistInput.addEventListener('input', showSuggestions);
        taglistInput.addEventListener('keydown', handleBackspace);
        document.addEventListener('click', hideSuggestions);

        function showSuggestions() {
            const query = taglistInput.value.toLowerCase();
            taglistSuggestions.innerHTML = '';

            if (query) {
                const suggestions = predefinedHobbies.filter(hobby => hobby.toLowerCase().includes(query) && !selectedHobbies.includes(hobby));

                suggestions.forEach(hobby => {
                    const suggestionDiv = document.createElement('div');
                    suggestionDiv.textContent = hobby;
                    suggestionDiv.addEventListener('click', () => addTag(hobby));
                    taglistSuggestions.appendChild(suggestionDiv);
                });

                taglistSuggestions.style.display = suggestions.length ? 'block' : 'none';
            } else {
                taglistSuggestions.style.display = 'none';
            }
        }

        function handleBackspace(event) {
            if (event.key === 'Backspace' && !taglistInput.value && selectedHobbies.length) {
                removeTag(selectedHobbies[selectedHobbies.length - 1]);
            }
        }

        function hideSuggestions(event) {
            if (event.target !== taglistInput) {
                taglistSuggestions.style.display = 'none';
            }
        }

        function addTag(hobby) {
            if (selectedHobbies.length < maxHobbies && !selectedHobbies.includes(hobby)) {
                selectedHobbies.push(hobby);
                updateTaglist();
            }
            taglistInput.value = '';
            taglistSuggestions.style.display = 'none';
        }

        function removeTag(hobby) {
            selectedHobbies = selectedHobbies.filter(h => h !== hobby);
            updateTaglist();
        }

        function updateTaglist() {
            taglistContainer.querySelectorAll('.taglist-tag').forEach(tag => tag.remove());

            selectedHobbies.forEach(hobby => {
                const tag = document.createElement('span');
                tag.className = 'taglist-tag';
                tag.textContent = hobby;

                const removeBtn = document.createElement('span');
                removeBtn.className = 'taglist-remove';
                removeBtn.textContent = 'x';
                removeBtn.addEventListener('click', () => removeTag(hobby));

                tag.appendChild(removeBtn);
                taglistContainer.insertBefore(tag, taglistInput);
            });

            hiddenInput.value = selectedHobbies.join(', ');
        }
    </script>
</body>
</html>
