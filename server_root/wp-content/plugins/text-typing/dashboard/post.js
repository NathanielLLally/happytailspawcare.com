window.copyBPlAdminShortcode = function (postID) {
  // Get the input element by ID
  var copyText = document.querySelector('#bPlAdminShortcode-' + postID + ' input');

  // Select the text field
  copyText.select();
  copyText.setSelectionRange(0, 99999); // For mobile devices

  // Copy the text inside the text field
  document.execCommand("copy");

  // Optional: Provide feedback to the user, e.g., change the tooltip text
  var tooltip = document.querySelector('#bPlAdminShortcode-' + postID + ' .tooltip');
  tooltip.innerHTML = "Copied Successfully!";
}



// document.addEventListener('DOMContentLoaded', () => {
//   const copyBtn = document.getElementById('ttbCopyBtn');
//   const input = document.getElementById('ttbShortcodeInput');
//   const message = document.getElementById('ttbCopyMessage');

//   if (copyBtn && input) {
//     copyBtn.addEventListener('click', async () => {
//       const shortcode = input.value;

//       if (navigator.clipboard && navigator.clipboard.writeText) {
//         try {
//           await navigator.clipboard.writeText(shortcode);
//           showCopiedMessage();
//         } catch (err) {
//           console.warn('Clipboard API failed, trying fallback...', err);
//           fallbackCopy(shortcode);
//         }
//       } else {
//         fallbackCopy(shortcode);
//       }
//     });
//   }

//   function fallbackCopy(text) {
//     const temp = document.createElement('textarea');
//     temp.value = text;
//     document.body.appendChild(temp);
//     temp.select();
//     temp.setSelectionRange(0, 99999);
//     try {
//       document.execCommand('copy');
//       showCopiedMessage();
//     } catch (err) {
//       console.error('Fallback copy failed:', err);
//       alert('Copy failed. Please copy manually.');
//     }
//     document.body.removeChild(temp);
//   }

//   function showCopiedMessage() {
//     message.style.display = 'block';
//     setTimeout(() => (message.style.display = 'none'), 2000);
//   }
// });


document.addEventListener('DOMContentLoaded', () => {
  const copyBtn = document.getElementById('ttbCopyBtn');
  const input = document.getElementById('ttbShortcodeInput');

  if (copyBtn && input) {
    copyBtn.addEventListener('click', async () => {
      const shortcode = input.value;

      // Try modern Clipboard API
      if (navigator.clipboard && navigator.clipboard.writeText) {
        try {
          await navigator.clipboard.writeText(shortcode);
          showCopiedMessage();
          return;
        } catch (err) {
          console.warn('Clipboard API failed, using fallback.', err);
          fallbackCopy(shortcode);
        }
      } else {
        fallbackCopy(shortcode);
      }
    });
  }

  function fallbackCopy(text) {
    const temp = document.createElement('textarea');
    temp.value = text;
    document.body.appendChild(temp);
    temp.select();
    temp.setSelectionRange(0, 99999);
    try {
      document.execCommand('copy');
      showCopiedMessage();
    } catch (err) {
      console.error('Fallback copy failed:', err);
      alert('Copy failed. Please copy manually.');
    }
    document.body.removeChild(temp);
  }

  function showCopiedMessage() {
    const originalText = copyBtn.textContent;
    copyBtn.textContent = '✅ Successfully Copied!';
    copyBtn.disabled = true;
    setTimeout(() => {
      copyBtn.textContent = originalText;
      copyBtn.disabled = false;
    }, 2000);
  }
});
