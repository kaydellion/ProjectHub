<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document Selector</title>
  <!-- Include Select2 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <!-- Include jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <!-- Include Select2 JS -->
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
</head>
<body>
  <h1>Select Document Types</h1>
  <form id="documentForm">
    <select id="documentSelect" class="select2" multiple="multiple" style="width: 100%;">
      <option value="doc">Word Document (.doc, .docx)</option>
      <option value="xls">Excel Spreadsheet (.xls, .xlsx)</option>
      <option value="ppt">PowerPoint Presentation (.ppt, .pptx)</option>
      <option value="pdf">PDF Document (.pdf)</option>
      <option value="txt">Text File (.txt)</option>
    </select>
    <div id="inputContainer"></div>
  </form>

  <script>
    $(document).ready(function() {
      // Initialize Select2
      $('.select2').select2();

      // Handle selection change
      $('#documentSelect').on('change', function() {
        var selectedValues = $(this).val();
        var inputContainer = $('#inputContainer');
        inputContainer.empty();

        if (selectedValues) {
          selectedValues.forEach(function(value) {
            var inputGroup = `
              <div class="input-group" id="input-${value}">
                <label for="${value}-pages">${value.toUpperCase()} Number of Pages:</label>
                <input type="number" id="${value}-pages" name="${value}-pages" min="1" required>
              </div>
            `;
            inputContainer.append(inputGroup);
          });
        }
      });
    });
  </script>
</body>
</html>