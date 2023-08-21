import { registerReactControllerComponents } from "@symfony/ux-react";

import React from "react";
import ReactDOM from "react-dom";


registerReactControllerComponents(
  require.context("./react/controllers", true, /\.(j|t)sx?$/)
);
