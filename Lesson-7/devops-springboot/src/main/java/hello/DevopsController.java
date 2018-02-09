package hello;

import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RequestParam;

/**
 * Created by r00xx<82049406@qq.com> on 2016/11/11.
 */

@Controller
public class DevopsController {

  @RequestMapping("/devops")
  public String greeting(@RequestParam(value = "name", required = false, defaultValue = "devops") String name, Model model) {
    model.addAttribute("name", name);
    return "devops";
  }
}
