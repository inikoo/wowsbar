// Jump the view to an element
export const jumpToElement = (idElement: string) => {
    const targetElement = document.getElementById(idElement);
    if (targetElement) {
      targetElement.scrollIntoView({ behavior: 'smooth' });
    }
}
