<?php

$htmlContent = '<p>wefh oiwheif @<span data-mentioned-user="apple"><strong>apple</strong> qwdfjqwf @</span><span data-mentioned-user="lemon"><strong>lemon</strong> dan @</span><span data-mentioned-user="watermelon"><strong>watermelon</strong> weifiwef </span><br></p>';

preg_match_all('/data-mentioned-user="([^"]+)"/', $htmlContent, $matches);

$mentionedUsers = $matches[1]; // Array berisi nilai dari atribut data-mentioned-user
print_r($mentionedUsers);

