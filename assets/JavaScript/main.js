// Smooth scroll with offset (header height)
const header = document.getElementById('mainHeader');
const navLinks = document.querySelectorAll('.nav-link');

function getHeaderHeight() {
  return header.getBoundingClientRect().height + 8; // sedikit offset extra
}

navLinks.forEach(link => {
  link.addEventListener('click', function(e){
    e.preventDefault();
    const targetId = this.getAttribute('data-target');
    const targetEl = document.getElementById(targetId);
    if(!targetEl) return;

    const top = targetEl.getBoundingClientRect().top + window.pageYOffset - getHeaderHeight();
    window.scrollTo({ top, behavior: 'smooth' });

    // 
    navLinks.forEach(l => l.classList.remove('active'));
    this.classList.add('active');
  });
});

//
const sections = document.querySelectorAll('section');
window.addEventListener('scroll', () => {
  const fromTop = window.scrollY + getHeaderHeight() + 10;

  sections.forEach(section => {
    const id = section.getAttribute('id');
    const top = section.offsetTop;
    const bottom = top + section.offsetHeight;

    const link = document.querySelector(`.nav-link[data-target="${id}"]`);
    if (!link) return;

    if (fromTop >= top && fromTop < bottom) {
      link.classList.add('active');
    } else {
      link.classList.remove('active');
    }
  });
});

// 
window.dispatchEvent(new Event('scroll'));
