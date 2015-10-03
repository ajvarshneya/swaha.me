var copyBtn = document.querySelector('#js-copy-button');

copyBtn.addEventListener('click', function(event) {
  var copy = document.querySelector('#js-submit-url');
  copy.select();

  try {
    var successful = document.execCommand('copy');
    var msg = successful ? 'successful' : 'unsuccessful';
    console.log('Copying text command was ' + msg);
  } catch (err) {
    console.log('Oops, unable to copy');
  }
});