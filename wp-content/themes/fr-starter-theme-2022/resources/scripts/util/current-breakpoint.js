let currentBreakpoint = () => {
    return window.getComputedStyle($('#app-sizer')[0], ':before')['content'].replaceAll('"', '');
}

exports.currentBreakpoint = currentBreakpoint;