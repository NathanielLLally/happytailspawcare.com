
const slug = "text-typing";
import { settingIcon, themeIcon } from "./icons";

export const dashboardInfo = (info) => {
  const { version, isPremium, hasPro } = info;

  const proSuffix = isPremium ? " Pro" : "";

  return {
    name: `Text Typing${proSuffix}`,
    displayName: `Text Typing${proSuffix} - Add Animated Typing Effects to Headings or Text`,
    description:
      "Check out our simple video tutorial that guides you through using this plugin step-by-step!",
    slug,
    logo: `https://ps.w.org/${slug}/assets/icon-128x128.png`,
    banner: `https://ps.w.org/${slug}/assets/banner-772x250.png`,
    // video: 'https://www.youtube.com/watch?v=milYZrqLJsE',
    // isYoutube: true,
    version,
    isPremium,
    hasPro,
    pages: {
      org: `https://wordpress.org/plugins/${slug}/`,
      // landing: `https://bplugins.com/products/${slug}/`,
      // docs: `https://bplugins.com/docs/${slug}/`,
      pricing: `https://bplugins.com/products/${slug}/#pricing`,
    },
    freemius: {
      product_id: 20170,
      plan_id: 33453,
      public_key: "pk_b0a805a4574f7a1db93e8859282de",
    },
    options: { title: "Text Typing" }
  };
};
export const demoInfo = {
  title: "Live Overview",
  description: "Click on any section to view it live",
  layout: "list",
  allInOneLabel: "See All Demos",
  allInOneLink: "https://bblockswp.com/demo/text-typing/",
  demos: [
    {
      icon: themeIcon,
      title: "Theme Default",
      description: "Applies the standard text typing style without extra effects.",
      category: "",
      type: "image",
      url: "https://i.ibb.co.com/PGzGBHgB/screenshot-1.png",
    },
    {
      icon: themeIcon,
      title: "Terminal",
      description: "Simulates a command-line style typing effect.",
      category: "",
      type: "image",
      url: "https://i.ibb.co.com/kgzrY6wC/screenshot-2.png",
    },
    {
      icon: themeIcon,
      title: "Star Flower",
      description: "Adds a colorful, animated typing effect with star-like accents.",
      category: "",
      type: "image",
      url: "https://i.ibb.co.com/wrPpcSp9/screenshot-3.png",
    },
    {
      icon: themeIcon,
      title: "Neon Grid",
      description: "Applies a glowing neon typing effect with a grid-style background.",
      category: "",
      type: "image",
      url: "https://i.ibb.co.com/vx9p948M/screenshot-4.png",
    },
    {
      icon: themeIcon,
      title: "Aurora Glow",
      description: "Creates a smooth, glowing typing effect inspired by aurora lights.",
      category: "",
      type: "image",
      url: "https://i.ibb.co.com/BK2j77vj/screenshot-5.png",
    },
    {
      icon: themeIcon,
      title: "Cyber Wave",
      description: "Applies a futuristic typing effect with dynamic wave-like motion.",
      category: "",
      type: "image",
      url: "https://i.ibb.co.com/XfTykpch/screenshot-6.png",
    },
    {
      icon: settingIcon,
      title: "Gutenberg Settings",
      description: "Provides customization options for managing the Gutenberg editor.",
      category: "",
      type: "image",
      url: "https://i.ibb.co.com/FNzF4F7/screenshot-7.png",
    },
  ],
};


export const pricingInfo = {
  cycles: [
    {
      cycle: "lifetime",
      label: "Lifetime",
      isDefault: true,
    },
  ],
  plans: [
    {
      name: "Single Site",
      quantity: 1,
      prices: {
        lifetime: "29",
      },
      pricePrefix: "",
      priceSuffix: "",
      isFeatured: false,
      note: "",
    },
    {
      name: "3 Sites",
      quantity: 3,
      prices: {
        lifetime: "79",
      },
      pricePrefix: "",
      priceSuffix: "",
      isFeatured: true,
      note: "",
    },
    {
      name: "Unlimited Sites",
      quantity: "null",
      prices: {
        lifetime: "199",
      },
      pricePrefix: "",
      priceSuffix: "",
      isFeatured: false,
      note: "",
    },
  ],
  features: [
    "Variant multiple stylish themes",
    "Custom Colors & Gradients",
    "Typing Speed Control",
    "Loop & Delay Options",
    "Cursor Styling",
    "Text Shadow Effects",
    "Prefix & Suffix Text",
    "Responsive Settings",
    "Premium Animations",
    "Optional Icons for Each Text",
    "Icon Customization",
    "ShortCode Powered",
  ],
  button: {
    label: "Buy Now ➜",
  },
  featured: {
    text: "Best Value",
  },
};

export const featureCompareInfo = {
  title: "Features",
  plans: [
    {
      id: "zxcvbnm", //important
      name: "Free Plan",
      color: "#485781",
    },
    {
      id: "lhmjqhk", //important
      name: `<span style='color: #485781;'>Pro Start from </span><span style='font-size: 1.3em;'>&dollar; 29/lifetime</span>`,
      color: "#146EF5",
    },
  ],
  features: [
    {
      label: "Add prefix around the typing",
      plans: ["zxcvbnm", "lhmjqhk"],
    },
    {
      label: "Add suffix around the typing",
      plans: ["zxcvbnm", "lhmjqhk"],
    },
    {
      label: "Configure the typing such as Type Speed",
      plans: ["zxcvbnm", "lhmjqhk"],
    },
    {
      label: "Configure the typing such as Start Delay",
      plans: ["zxcvbnm", "lhmjqhk"],
    },
    {
      label: "Configure the typing such as Back Speed, Back Delay",
      plans: ["zxcvbnm", "lhmjqhk"],
    },
    {
      label: "Configure the typing such as Back Speed, Back Delay",
      plans: ["zxcvbnm", "lhmjqhk"],
    },
    {
      label: "Configure enable/disable Shuffle, Fade Out Effect, Loop, and Cursor.",
      plans: ["zxcvbnm", "lhmjqhk"],
    },
    {
      label: "Variant multiple stylish themes",
      plans: ["lhmjqhk"],
    },
    {
      label: "Custom Colors & Gradients",
      plans: ["lhmjqhk"],
    },
    {
      label: "Adjust Typing Speed Control",
      plans: ["lhmjqhk"],
    },
    {
      label: "Repeat or pause typing with precise timing",
      plans: ["lhmjqhk"],
    },
    {
      label: "Change style, size, and color for a personalized look",
      plans: ["lhmjqhk"],
    },
    {
      label: "Add depth, glow, or emphasis",
      plans: ["lhmjqhk"],
    },
    {
      label: "Responsive Settings perfect to all device",
      plans: ["lhmjqhk"],
    },
    {
      label: "Exclusive smooth effects available only in Pro",
      plans: ["lhmjqhk"],
    },
    {
      label: "Optional Icons for Each Text",
      plans: ["lhmjqhk"],
    },
    {
      label: "Icon Customization fo style freely",
      plans: ["lhmjqhk"],
    },
    {
      label: "Icon Customization fo style freely",
      plans: ["lhmjqhk"],
    },
    {
      label: "Used ShortCode",
      plans: ["lhmjqhk"],
    }
  ],
};