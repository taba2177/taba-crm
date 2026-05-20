export { HeroSection } from './hero-section';
export { AboutSection } from './about-section';
export { ServiceSection } from './service-section';
export { ProjectsSection } from './projects-section';
export { WorksSection } from './works-section';
export { CardsSection } from './cards-section';
export { BenefitSection } from './benefit-section';
export { WhyChooseUsSection } from './why-choose-us-section';

import { Type } from '@angular/core';
import { HeroSection } from './hero-section';
import { AboutSection } from './about-section';
import { ServiceSection } from './service-section';
import { ProjectsSection } from './projects-section';
import { WorksSection } from './works-section';
import { CardsSection } from './cards-section';
import { BenefitSection } from './benefit-section';
import { WhyChooseUsSection } from './why-choose-us-section';

export const SECTION_COMPONENT_MAP: Record<string, Type<any>> = {
  'hero': HeroSection,
  'about': AboutSection,
  'our-service': ServiceSection,
  'our-projects': ProjectsSection,
  'our-works': WorksSection,
  'four-cards': CardsSection,
  'service-benefit': BenefitSection,
  'why-choose-us': WhyChooseUsSection,
};
