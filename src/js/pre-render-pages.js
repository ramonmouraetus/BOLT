(function () {
  try {
    const observer = new IntersectionObserver(checkElementVisibility);
    let ctaList = document.querySelectorAll('[id^="btn-brius"]');
    if (ctaList.length) {
      for (let i = 0; i < ctaList.length; i++) {
        const url = ctaList[i].href;
        if (url.substring(0, window.location.origin.length) == window.location.origin) {
          observer.observe(ctaList[i]);
        }
      }
    }
    function checkElementVisibility(entries, observer) {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          let script = document.createElement("script");
          script.type = "speculationrules";
          const cta = entry.target;
          script.text = `
          {
            "prerender": [
              {
                "source": "list",
                "urls": [${JSON.stringify(cta.href)}]
              }
            ]
          }
          `;
          document.body.appendChild(script);
          observer.unobserve(cta);
        }
      });
      
    }
  } catch (t) {
    console.log(t);
  }
})();
