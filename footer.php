<!-- hiasan -->
<div class="biru"></div>
<div class="gambar-kanan">
  <div class="iki-gambar"></div>
</div>
<!-- Akhir hiasan -->

<div class="madeby">
  <p>
    Made with <i class="fa fa-heart"></i> by <a href="http://instagarm.com/aguzztn54" target="_blank"
      rel="noopener noreferrer">Agus</a><br>Lulusan 2018
  </p>
</div>

<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="assets/js/libs/jquery.min.js"></script>
<script src="assets/js/libs/bootstrap.bundle.min.js"></script>
<script src="assets/js/libs/easing.js"></script>
<script src="assets/js/libs/wow.js"></script>
<script src="assets/js/main.js"></script>

<?php echo $script; ?>

<script>
const inputs = document.querySelectorAll(".input");


function addcl() {
  let parent = this.parentNode.parentNode;
  parent.classList.add("focus");
}

function remcl() {
  let parent = this.parentNode.parentNode;
  if (this.value == "") {
    parent.classList.remove("focus");
  }
}

inputs.forEach(input => {
  input.addEventListener("focus", addcl);
  input.addEventListener("blur", remcl);
});
</script>
</body>

</html>